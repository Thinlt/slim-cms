<?php
/**
 * Slim framework
 * Author: Tit - Robert - Thinlt
 */
require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/Api.php';

$app = new \App(require BP.DS.'etc'.DS.'config.php');

//add middleware
$app->add(new \Api\Authorization());

//Run API app
$api = new \Api();
$api->setup(require __DIR__.DS.'Setup.php');
$api->run($app);


$app->run();
