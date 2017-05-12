import os
import time
import shutil
from util import merge, remove, pprint
from functools import reduce
# third-party
import jinja2 as j2
import yaml
import ftputil
from ftpsync.synchronizers import UploadSynchronizer
from ftpsync.targets import FsTarget
from ftpsync.ftp_target import FtpTarget
from urllib.parse import urlparse
import fabric.api as fab
import fabric.contrib.console as console
from fabric.context_managers import lcd

templates = {
    'settings.php.j2': 'bitrix/.settings.php',
    'dbconn.php.j2': 'bitrix/php_interface/dbconn.php'
}
asset_build_command = './build.sh'
git_ftp_syncroot = 'public'


def config():
    defaults = {
        'generated_message': 'This file is generated by Fabric, all changes will be lost',
        'local': False
    }
    return reduce(merge, [
        dict(defaults),
        yaml.load(open('vars.yml')),
        yaml.load(open('vars/secrets.yml'))
    ])


def init():
    for role in cfg['roles']:
        if role != 'all':
            for host in cfg['roles'][role].get('hosts', []):
                # plug into fabric
                fab.env.roledefs.setdefault(role, []).append(host)


# global state

state = {
    'verbose': False
}
cfg = config()
templates_path = os.path.join(os.path.dirname(__file__), 'templates')
j2env = j2.Environment(loader=j2.FileSystemLoader(templates_path),
                       undefined=j2.StrictUndefined,
                       trim_blocks=True,
                       lstrip_blocks=True)
# fabric setup
init()


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


def backup_filename(filename):
    return '{}.{}~'.format(filename, time.strftime('%Y-%m-%d@%H:%M:%S'))


def backup_file(env, path):
    [directory, filename] = os.path.split(path)
    dest = os.path.join(directory, backup_filename(filename))
    if env['local'] and os.path.exists(path):
        shutil.copy(path, dest)
    else:
        # TODO optimize
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
    console.confirm('write to {}?'.format(path))
    if env['local']:
        with open(path, 'w') as file:
            file.write(contents)
    else:
        # TODO optimize
        host = ftp_host(env)
        with host.open(path, 'w') as file:
            file.write(contents)


# TODO this and `write_file` feel generalizable
def push_file(env, local, remote, backup=True):
    if os.path.exists(local):
        if backup:
            # TODO only if it's changed
            backup_file(env, remote)
        console.confirm('write to {}?'.format(remote))
        if env['local']:
            shutil.copy2(local, remote)
        else:
            # TODO optimize
            host = ftp_host(env)
            with open(local) as source:
                with host.open(remote, 'w') as target:
                    host.copyfileobj(source, target)


# TODO refactor
def docroot_path(env, rel_path):
    return os.path.join(env['document_root'], rel_path)


# TODO verbose output
# TODO memoize?
def ftp_host(env):
    ftp = env['ftp']
    url = urlparse(ftp['url'])
    if url.scheme != 'ftp':
        fab.warn('non-ftp url scheme, may not be supported')
    ret = ftputil.FTPHost(url.netloc, ftp['user'], ftp['password'])
    ret.chdir(url.path)
    assert ret.path.exists('.git-ftp.log')
    return ret


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


@fab.task(alias='gitftp')
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
        push_file(env, 'files/stage/robots.txt', docroot_path(env, 'robots.txt'))


@fab.task
def upload_dir(local, remote, dry_run=False):
    ftp = environment()['ftp']
    extra_opts = {'ftp_debug': 1 if state['verbose'] else 0}
    url = urlparse(ftp['url'])
    if url.scheme != 'ftp':
        fab.warn('non-ftp url scheme, may not be supported')
    local_target = FsTarget(local, extra_opts=extra_opts)
    remote_target = FtpTarget(
        path=os.path.join(url.path, remote),
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
def verbose():
    state['verbose'] = True


@fab.task(default=True)
def deploy():
    env = environment()
    # TODO
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
            fab.execute(upload_dir('../public/local/' + rel_path, 'local/' + rel_path))
        # TODO `git-ftp init` for initial deployment?
        # git-ftp push
        fab.execute(git_ftp, 'push')
        # clear bitrix cache?
        # migrate db
        # notify in slack if remote
        # maintenance mode off
