<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
define('APP_ROOT', __DIR__);

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'error' => [
                // Should be set to false in production
                'display_error_details' => true,

                // Parameter is passed to the default ErrorHandler
                // View in rendered output by enabling the "displayErrorDetails" setting.
                // For the console and unit tests we also disable it
                'log_errors' => true,

                // Display error details in error log
                'log_error_details' => true,
            ],
            'determineRouteBeforeAppMiddleware' => false,
            'doctrine' => [
                // if true, metadata caching is forcefully disabled
                'dev_mode' => true,

                // path where the compiled metadata info will be cached
                // make sure the path exists and it is writable
                'cache_dir' => APP_ROOT . '/var/doctrine',

                // you should add any other path containing annotated entity classes
                'metadata_dirs' => [APP_ROOT . '/src/Domain'],

                'connection' => [
                    'driver' => 'pdo_mysql',
                    'host' => 'localhost',
                    'port' => 3306,
                    'dbname' => 'db_name',
                    'user' => 'db_user',
                    'password' => 'db_password',
                    'charset' => 'utf-8'
                ]
            ],
        ],
    ]);
};
