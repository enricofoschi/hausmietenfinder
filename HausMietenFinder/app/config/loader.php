<?php

error_reporting(E_ALL);

require_once __DIR__ . '/../vendors/autoload.php';

$loader = new \Phalcon\Loader();

$loader->registerNameSpaces(
  array(
      "HausMietenFinder\Services" => $config->application->servicesDir . "HausMietenFinder",
      "DevDes\Services\Messaging" => $config->application->servicesDir . "DevDes/Messaging",
      "HausMietenFinder\Models" => $config->application->modelsDir
  )
);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->servicesDir
    )
)->register();
