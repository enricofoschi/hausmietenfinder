<?php

error_reporting(E_ALL);

try {

    include __DIR__ . '/loader.php';
    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}