<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use HausMietenFinder\Services\Immobiliaren24Service;
use HausMietenFinder\Services\GoogleMapsService;
use DevDes\Services\Messaging\RabbitMQService;
use DevDes\Services\Data\MongoDBService;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                "compileAlways" => true
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->set('router', function() {
    $router = new \Phalcon\Mvc\Router();

    $router->add("/-:controller/:action", array(
        'controller' => 1,
        'action' => 2,
    ));

    $router->add("/{any:[^-].*}", array(
        'controller' => 'index',
        'action' => 'index',
    ));

    return $router;
});

$di->set('dispatcher', function() use ($di) {
    $evManager = $di->getShared('eventsManager');

    $evManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {

        switch ($exception->getCode()) {
            case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward(
                    array(
                        'controller' => 'index',
                        'action'     => 'route404',
                    )
                );
                return false;
            default:
                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'error500',
                    'params' => array(
                        "exception" => $exception->getMessage()
                    )
                ));
                return false;
        }
    });

    $dispatcher = new PhDispatcher();
    $dispatcher->setEventsManager($evManager);
    return $dispatcher;
}, true);

// Simple database connection to localhost
$di->set('mongodb_service', function() use($config) {
    return new MongoDBService($config->mongodb->database);
}, true);

// Simple database connection to localhost required for Phalcon model mappings
$di->set('mongo', function() use($di) {
    return $di['mongodb_service']->getDatabase();
}, true);

$di->set('collectionManager', function(){
    return new Phalcon\Mvc\Collection\Manager();
}, true);

$di->set('immobiliaren24_service', function() {
    return new Immobiliaren24Service();
}, true);

$di->set('googlemaps_service', function() {
    return new GoogleMapsService();
}, true);

$di->set('rabbitmq_service', function() use($config) {
    return new RabbitMQService(
        $config->rabbitmq->host,
        $config->rabbitmq->port,
        $config->rabbitmq->username,
        $config->rabbitmq->password
    );
});

$di->set('redis_service', function() use($config) {
    return new RedisService(
        $config->redis->host,
        $config->redis->port,
        $config->redis->db
    );
});