<?php
/**
 * Yii Application Config
 *
 * Edit this file at your own risk!
 *
 * The array returned by this file will get merged with
 * vendor/craftcms/cms/src/config/app.php and app.[web|console].php, when
 * Craft's bootstrap script is defining the configuration for the entire
 * application.
 *
 * You can define custom modules and system components, and even override the
 * built-in system components.
 *
 * If you want to modify the application config for *only* web requests or
 * *only* console requests, create an app.web.php or app.console.php file in
 * your config/ folder, alongside this one.
 */
use craft\helpers\App;

$redisUrl = App::env('REDIS_URL');
//print_r(App::env('REDIS_URL'));
//print_r(App::env('REDIS_USE_SSL'));
//die();

if (!$redisUrl) {
    exit('app.php: The redis database is not reachable as configured:' . App::env('REDIS_URL'));
}

$redisSettings =  [
    'id' => App::env('APP_ID') ?: 'CraftCMS',
    'modules' => [
        'site-module' => [
            'class' => \modules\sitemodule\SiteModule::class,
        ],
    ],
    'bootstrap' => ['site-module'],
    'components' => [
        'cache' => [
            'class' => yii\redis\Cache::class,
            'defaultDuration' => 86400,
            'keyPrefix' => App::env('APP_ID') ?: 'CraftCMS',
            'redis' => [
                'hostname' => parse_url(App::env('REDIS_URL'), PHP_URL_HOST),
                'port' => parse_url(App::env('REDIS_URL'), PHP_URL_PORT),
                'database' => App::env('REDIS_USE_SSL') ? App::env('REDIS_CRAFT_DB') : null,
                'password' => App::env('REDIS_USE_SSL') ? parse_url(App::env('REDIS_URL'), PHP_URL_PASS) : null,
                'useSSL' => App::env('REDIS_USE_SSL'),
                'contextOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
        'deprecator' => [
            'throwExceptions' => App::env('DEV_MODE'),
        ],
        'queue' => [
            'class' => craft\queue\Queue::class,
            'ttr' => 10 * 60,
        ],
        'redis' => [
            'class' => yii\redis\Connection::class,
            'hostname' => parse_url(App::env('REDIS_URL'), PHP_URL_HOST),
            'port' => parse_url(App::env('REDIS_URL'), PHP_URL_PORT),
            'database' => App::env('REDIS_USE_SSL') ? App::env('REDIS_CRAFT_DB') : null,
            'user' => App::env('REDIS_USE_SSL') ? parse_url(App::env('REDIS_URL'), PHP_URL_USER) : null,
            'password' => App::env('REDIS_USE_SSL') ? parse_url(App::env('REDIS_URL'), PHP_URL_PASS) : null,
            'useSSL' => App::env('REDIS_USE_SSL'),
            'contextOptions' => [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ],
            ],
        ],
    ],
];

return $redisSettings;
