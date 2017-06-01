<?php

define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
$GLOBALS['DBType'] = 'mysql';
$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/..');
include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
// manual saving of DB resource
global $DB;
$app = \Bitrix\Main\Application::getInstance();
$con = $app->getConnection();
$DB->db_Conn = $con->getResource();
// "authorizing" as admin
$_SESSION['SESS_AUTH']['USER_ID'] = 1;

$config = include realpath(__DIR__.'/../bitrix/.settings.php');
$default = $config['connections']['value']['default'];

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds'
    ],
    'templates' => [
        'file' => 'db/migration_template.php.txt'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'default',
        'default' => [
            'adapter' => 'mysql',
            'host' => $default['host'] === 'localhost' ? '127.0.0.1' : $default['host'],
            'name' => $default['database'],
            'user' => $default['login'],
            'pass' => $default['password']
        ]
    ]
];