<?php
return [
    //数据库的相关配置
    'DB' => [
        'db' => 'mysql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'phpFrameworkStudy',
        'port' => '3306',

        'charset' => 'utf8',
        'prefix' => 't_',     //前缀
    ],
    //SESSION的相关配置
    'SESSION' => [
        'SESSION_PREFIX' => 't',
        'PHPSESSID_HTTPONLY' => true,   //保存在Cookie中PHPSESSID是否使用HttpOnly
    ],
];
