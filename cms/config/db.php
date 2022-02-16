<?php
/**
 * Database Configuration
 *
 * All of your system's database connection settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/DbConfig.php.
 *
 * @see craft\config\DbConfig
 */

use craft\helpers\App;

$url = getenv('CLEARDB_DATABASE_URL');

if (!$url) {
  exit('The database is not reachable as configured.');
}

$prodHost = getenv('CLEARDB_DATABASE_CLEARDB_HOSTNAME_1');

$dbparts = parse_url($url);

return [
    'dsn' => App::env('DB_DSN') ?: null,
    'driver' => $dbparts['scheme'],
    // 'server' => $prodHost ? $prodHost : $dbparts['host'],
    'server' => $dbparts['host'],
    'port' => $dbparts['port'],
    'database' => ltrim($dbparts['path'], '/'),
    'user' => $dbparts['user'],
    'password' => $dbparts['pass'],
    'schema' => $dbparts['scheme'],
    'tablePrefix' => App::env('DB_TABLE_PREFIX') ?: "",
];
