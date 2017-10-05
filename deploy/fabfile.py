import os
import time
import shutil
import json
import atexit
from util import merge, remove, pprint
from functools import reduce
from urllib.parse import urlparse
import requests
import jinja2 as j2
import yaml
import ftputil
import pexpect
from ftpsync.synchronizers import UploadSynchronizer
from ftpsync.targets import FsTarget
from ftpsync.ftp_target import FtpTarget
import fabric.api as fab
import fabric.contrib.console as console
import fabric.contrib.files as files
from fabric.context_managers import cd, lcd

config_path = '../config'
templates = {
    'settings.php.j2': 'bitrix/.settings.php',
    'dbconn.php.j2': 'bitrix/php_interface/dbconn.php'
}
asset_build_command = 'npm install && npm run build'
# TODO needs configuration such as the url to run tests against
test_command = 'npm test'
git_ftp_syncroot = 'public'


def config():
    defaults = {
        'generated_message': 'This file is generated by Fabric, all changes will be lost',
        'local': False
    }
    return reduce(merge, [
        dict(defaults),
        yaml.load(open(os.path.join(config_path, 'vars.yml'))),
        yaml.load(open(os.path.join(config_path, 'secrets.yml')))
    ])


def init():
    for role in cfg['roles']:
        if role != 'all':
            for host in cfg['roles'][role].get('hosts', []):
                # plug into fabric
                fab.env.roledefs.setdefault(role, []).append(host)


cache_path = 'bitrix/cache'
# global state
state = {
    'verbose': False,
    'connections': {}
}
cfg = config()
templates_path = os.path.join(os.path.dirname(__file__), config_path)
j2env = j2.Environment(loader=j2.FileSystemLoader(templates_path),
                       undefined=j2.StrictUndefined,
                       trim_blocks=True,
                       lstrip_blocks=True)
# fabric setup
init()
atexit.register(lambda: [conn.close() for conn in state['connections'].values()])


def environment(roles=None, host=None):
    if roles is None:
        roles = fab.env.roles
    if host is None:
        host = fab.env.host
    if not host:
        fab.warn('no host')
    role_configs = [cfg['roles'][role] for role in roles]
    global_vars = remove(cfg, ['roles', 'hosts'])
    ret = reduce(merge, [global_vars, cfg['hosts'][host] if host else [], cfg['roles']['all']] + role_configs)
    if 'document_root' not in ret:
        url = urlparse(ret['ftp']['url'])
        ret['document_root'] = url.path
    return ret


# TODO stop doing it manually
def init_fabric_host(ssh):
    fab.env.host_string = '{}@{}'.format(ssh['user'], ssh['host'])
    fab.env.password = ssh['password']


def backup_filename(filename):
    return '{}.{}~'.format(filename, time.strftime('%Y-%m-%d@%H:%M:%S'))


def backup_file(env, path):
    [directory, filename] = os.path.split(path)
    dest = os.path.join(directory, backup_filename(filename))
    if env['local'] and os.path.exists(path):
        shutil.copy(path, dest)
    # elif 'ssh' in env:
    #     init_fabric_host(env['ssh'])
    #     fab.run('cp {} {}'.format(path, dest))
    else:
        host = ftp_host(env)
        if host.path.exists(path):
            with host.open(path) as source:
                with host.open(dest, 'w') as target:
                    host.copyfileobj(source, target)
    fab.puts('backup created: {}'.format(dest))


def write_file(env, path, contents, backup=True):
    if backup:
        # TODO only if it's changed
        backup_file(env, path)
    if console.confirm('write to {}?'.format(path)):
        if env['local']:
            with open(path, 'w') as file:
                file.write(contents)
        else:
            host = ftp_host(env)
            with host.open(path, 'w') as file:
                file.write(contents)


# TODO this and `write_file` feel generalizable
def push_file(env, local, remote, backup=True):
    if os.path.exists(local):
        if backup:
            # TODO only if it's changed
            backup_file(env, remote)
        if console.confirm('write to {}?'.format(remote)):
            if env['local']:
                shutil.copy2(local, remote)
            else:
                host = ftp_host(env)
                with open(local) as source:
                    with host.open(remote, 'w') as target:
                        host.copyfileobj(source, target)


# TODO refactor
def docroot_path(env, rel_path):
    return os.path.join(env['document_root'], rel_path)


# TODO verbose output
def ftp_host(env):
    ftp = env['ftp']
    # TODO refactor: pick a better id
    conn_id = ftp['url']
    if conn_id in state['connections']:
        return state['connections'][conn_id]
    url = urlparse(ftp['url'])
    if url.scheme != 'ftp':
        fab.warn('non-ftp url scheme, may not be supported')
    fab.puts('opening a new connection to {}'.format(url.netloc))
    ret = ftputil.FTPHost(url.netloc, ftp['user'], ftp['password'])
    ret.chdir(url.path)
    assert ret.path.exists('.git-ftp.log')
    state['connections'][conn_id] = ret
    return ret


def last_commit_sha():
    return fab.local('git log -1 --format="%h"', capture=True)


@fab.task
def print_env():
    pprint(environment())


@fab.task
def push_configs():
    env = environment()
    # TODO diff with existing and print
    for name, rel_path in templates.items():
        contents = j2env.get_template(name).render(env)
        write_file(env, docroot_path(env, rel_path), contents)


@fab.task
def git_ftp(args):
    ftp = environment()['ftp']
    git_ftp_args = ['--user', ftp['user'], '--passwd', ftp['password'], ftp['url'], '--syncroot', git_ftp_syncroot]
    with lcd('..'):
        fab.local('git-ftp {} {}'.format(args, ' '.join(git_ftp_args)))


@fab.task
def push_robots():
    env = environment()
    if 'stage' in fab.env.roles:
        fab.puts('copying staging robots.txt')
        push_file(env, os.path.join(config_path, 'stage/robots.txt'), docroot_path(env, 'robots.txt'))


@fab.task
def upload_dir(local, remote, dry_run=False, opts=None):
    env = environment()
    if 'ssh' in env:
        ssh = env['ssh']
        rsync_opts = ['--recursive', '--archive', '--compress', '--itemize-changes']
        if dry_run:
            rsync_opts.append('--dry-run')
        if state['verbose']:
            rsync_opts.append('--verbose')
        # trailing slash is important, see rsync documentation
        src = local + '/'
        abs_remote = os.path.join(ssh['document_root'], remote)
        dest = '{}@{}:{}'.format(ssh['user'], ssh['host'], abs_remote)
        args = rsync_opts + ([] if opts is None else opts) + [src, dest]
        rsync_cmd = 'rsync {}'.format(' '.join(args))
        fab.puts(rsync_cmd)
        proc = pexpect.spawn(rsync_cmd, encoding='utf8', logfile=os.sys.stdout, timeout=5*60)
        proc.expect('password')
        proc.sendline(ssh['password'])
        proc.expect(pexpect.EOF)
    else:
        ftp = env['ftp']
        host = ftp_host(env)
        extra_opts = {'ftp_debug': 1 if state['verbose'] else 0}
        url = urlparse(ftp['url'])
        if url.scheme != 'ftp':
            fab.warn('non-ftp url scheme, may not be supported')
        abs_remote = os.path.join(url.path, remote)
        if not host.path.exists(abs_remote):
            fab.warn('remote path is missing: {}'.format(abs_remote))
            if console.confirm('create missing directories?'):
                host.makedirs(abs_remote)
        local_target = FsTarget(local, extra_opts=extra_opts)
        remote_target = FtpTarget(
            path=abs_remote,
            host=url.netloc,
            username=ftp['user'],
            password=ftp['password'],
            extra_opts=extra_opts)
        opts = {
            'force': False,
            # TODO
            'delete_unmatched': False,
            'verbose': 3,
            'execute': True,
            'dry_run': dry_run
        }
        s = UploadSynchronizer(local_target, remote_target, opts)
        try:
            s.run()
        except KeyboardInterrupt:
            fab.warn('aborted by user')
        finally:
            s.local.close()
            s.remote.close()
        stats = s.get_stats()
        pprint(stats)


@fab.task
def clear_cache(dry_run=False):
    env = environment()
    if 'ssh' in env:
        init_fabric_host(env['ssh'])
        path = os.path.join(env['ssh']['document_root'], cache_path)
        if console.confirm('remove everything in directory {}?'.format(path), False):
            with cd(path):
                # removing stuff is spooky, you can't be too paranoid
                if not files.exists('css'):
                    fab.warn('no "css" in cache directory. double-check the path.')
                    if not console.confirm('remove anyway?', False):
                        fab.abort('aborted by user')
                if dry_run:
                    fab.run('echo *')
                else:
                    fab.run('rm -rf *')
    else:
        fab.abort('no implementation for non-ssh hosts yet. sorry.')


@fab.task
def test():
    # TODO refactor cwd
    cwd = '../public/local'
    with lcd(cwd):
        fab.local(test_command)


@fab.task
def verbose():
    state['verbose'] = True


@fab.task
def slack(text):
    env = environment()
    response = requests.post(env['slack']['webhook_url'], data={
        'payload': json.dumps({
            'channel': '#dev-bots',
            'username': 'webhookbot',
            'text': text,
            'icon_emoji': ':robot_face:'
        })
    })
    fab.puts(response.text)


@fab.task
def ensure_not_dirty():
    output = fab.local('git diff --shortstat 2> /dev/null | tail -n1', capture=True)
    if output != '':
        fab.abort('dirty git repo')


@fab.task
def upload_upload():
    fab.execute(upload_dir, '../public/upload', 'upload', opts=['--ignore-existing'])


@fab.task
def deploy(skip_slack=False):
    env = environment()
    fab.execute(ensure_not_dirty)
    # TODO
    # fab.execute(test)
    # maintenance mode on
    # push configs
    fab.execute(push_configs)
    # push staging robots.txt
    fab.execute(push_robots)
    # TODO refactor cwd
    cwd = '../public/local'
    with lcd(cwd):
        # local composer install
        if os.path.exists(os.path.join(cwd, 'composer.json')):
            fab.local('composer install')
        # local npm install and build assets
        fab.local(asset_build_command)
    if not env['local']:
        # sync directories: build, composer vendor, mockup
        for rel_path in ['templates/main/build', 'vendor']:
            # TODO optimize composer's vendor sync: look for changes in composer.json?
            fab.execute(upload_dir, '../public/local/' + rel_path, 'local/' + rel_path)
        fab.execute(upload_upload)
        # TODO `git-ftp init` for initial deployment?
        # git-ftp push
        fab.execute(git_ftp, 'push')
        # clear bitrix cache
        fab.execute(clear_cache)
        # TODO warm up service data cache
        fab.puts('TODO warm up service data cache')
        # migrate db
        # notify in slack if remote
        name = ', '.join(fab.env.roles)
        if not skip_slack:
            fab.execute(slack, 'Deployed to `{}` at {}, commit: {}'.format(name, env['ftp']['url'], last_commit_sha()))
        # maintenance mode off
