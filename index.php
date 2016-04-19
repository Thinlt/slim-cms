<?php
/**
 * Slim framework
 * Author: Tit - Robert - Thinlt
 */
require __DIR__.'/app/bootstrap.php';

$app = new \Slim\Slim(require 'etc/config.php');

//add middleware
$app->add(new \SlimApp());

//routing
Route::match($app);

//Default 404 page
$app->notFound(function () use ($app) {
    $app->render('page/404.html');
});


$app->run();


/**
 * For debug only
 */
echo "<pre>";
var_dump($app->request->get());
echo "</pre>";



