<?php
/**
 * Yii Web Application Config
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
 * This application config is applied only for *only* web requests
 */

use craft\helpers\App;



return [
    'components' => [
        'session' => static function() {
            // Get the default component config
            $config = App::sessionConfig();

            $redrisUrl = App::env('REDIS_URL');

            if (!$redrisUrl) {
              exit('The redis database is not reachable as configured.');
            }

            $redissParts = parse_url($redrisUrl);

            // Override the class to use Redis' session class and our config settings
            $config['class'] = yii\redis\Session::class;
            $config['keyPrefix'] = App::env('APP_ID') ?: 'CraftCMS';
            $config['redis'] = [
                'hostname' => $redissParts['host'],
                'port' => $redissParts['port'],
                'database' => App::env('REDIS_DEFAULT_DB'),
            ];
            // Add password to config if it is set
            if (isset($redissParts['pass']) && $redissParts['pass'] !== '') {
                $config['redis']['password'] = $redissParts['pass'];
            }


            // Instantiate and return it
            return Craft::createObject($config);
        },
    ],
];
