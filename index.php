<?php
/**
 * Slim framework
 * Author: Tit - Robert - Thinlt
 */
require __DIR__.'/app/bootstrap.php';

$app = new \App(require 'etc/config.php');

//add middleware
$app->add(new \Middleware());

//routing
Router::match($app);

//Default 404 page
$app->notFound(function () use ($app) {
    $app->render('page/404.html');
});


$app->run();




