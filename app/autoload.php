<?php

/**
 * @var ClassLoader $loader
 */

require_once __DIR__.'/../vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();

$loader->addPsr4('Router\\', __DIR__.'/Router/');
$loader->addPsr4('Controller\\', __DIR__.'/Controller/');
$loader->addPsr4('controllers\\', __DIR__.'/controllers/');
$loader->addPsr4('Model\\', __DIR__.'/Model/');
$loader->addPsr4('View\\', __DIR__.'/View/');

$loader->register(true);
