<?php

final class Route {
    public static function match($app){
        $req = $app->request;
        //$rootUri = $req->getRootUri();
        $resourceUri = $req->getResourceUri();

        $controllerPath = explode('/', trim($resourceUri, '/'));

        if(!isset($controllerPath[0])){
            $app->notFound();
        }

        if(!isset($controllerPath[1])){
            $controllerPath[1] = 'index';
        }

        if(!isset($controllerPath[2])){
            $controllerPath[2] = 'index';
        }

        //routing frontend
        $app->map($resourceUri,
            function() use ($app, $controllerPath) {
                $router = new \Router\Frontend();
                $router->dispatch($controllerPath[0], $controllerPath[1], $controllerPath[2], $app);
            })->via('GET', 'POST', 'PUT', 'DELETE');
    }
}


