<?php
/**
 * Slim framework
 * Author: Tit - Robert - Thinlt
 */
require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/Api.php';

$app = new \App(array('debug' => true));

//add middleware
$app->add(new \Api\Authorization());

$req = $app->request;
$rootUri = $req->getRootUri();
$resourceUri = $req->getResourceUri();
//routing

$api = new \Api();
$api->setup(require __DIR__.DS.'Setup.php');
$api->run($app);
//$api->match($resourceUri, $app);

$app->run();
