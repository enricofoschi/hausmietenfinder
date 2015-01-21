<?php

return new \Phalcon\Config(array(
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'servicesDir'    => __DIR__ . '/../../app/services/'
    ),
    'rabbitmq' => array(
        'host' => 'localhost',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest'
    ),
    'redis' => array(
        'host' => 'localhost',
        'port' => 6379,
        'db' => 2
    ),
    'immobilien24' => array(
        'api_secret'        => '/'
    ),
    'mongodb' => array(
        'database' => 'hausmietenfinder'
    )
));
