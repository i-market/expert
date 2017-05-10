<?php

// http://epages.su/blog/migratsii-bazy-dannykh-v-1s-bitirks-proektakh-s-ispolzovaniem-phinx.html

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
// 'authorizing' as admin
$_SESSION['SESS_AUTH']['USER_ID'] = 1;


$config = include realpath(__DIR__.'/../bitrix/.settings.php');

return [
    'paths' => [
        'migrations' => 'migrations',
        'seeds' => 'seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'expert',
        'expert' => [
            'adapter' => 'mysql',
            'host' => $config['connections']['value']['default']['host'],
            'name' => $config['connections']['value']['default']['database'],
            'user' => $config['connections']['value']['default']['login'],
            'pass' => $config['connections']['value']['default']['password']
        ]
    ]
];