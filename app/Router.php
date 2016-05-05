<?php

final class Router {
    public static function match($app){
        $req = $app->request;
        $rootUri = $req->getRootUri();
        $resourceUri = $req->getResourceUri();

        //routing admin
        $admin_url = ($app->config('admin_url_path'))?$app->config('admin_url_path'):'admin';
        if($resourceUri == '/'.$admin_url){
            $app->map('/'.$admin_url,
                function() use ($resourceUri, $app) {
                    $router = new \Router\Frontend();
                    if($router->findRoute('admin/admin')){
                        $router->dispatch('admin/admin', $app);
                    }else{
                        $router->dispatch('admin/index', $app);
                    }
                })->via('GET');
            return;
        }

        //routing frontend
        if($resourceUri != '/' && $resourceUri != '/home' && $resourceUri != '/index'){
            $app->map($resourceUri,
                function() use ($resourceUri, $app) {
                    $router = new \Router\Frontend();
                    $router->dispatch($resourceUri, $app);
                })->via('GET', 'POST', 'PUT', 'DELETE');
        }else{
            //map route home
            $app->map('/',
                function() use ($resourceUri, $app) {
                    $router = new \Router\Frontend();
                    if($router->findRoute('home')){
                        $router->dispatch('home', $app);
                    }else{
                        $router->dispatch('index', $app);
                    }
                })->via('GET');
            $app->map('/:home',
                function($home) use ($resourceUri, $app) {
                    $router = new \Router\Frontend();
                    if($home == 'home' || $home == 'index'){
                        if($router->findRoute('home')){
                            $router->dispatch('home', $app);
                        }elseif($router->findRoute('index')){
                            $router->dispatch('index', $app);
                        }
                    }else{
                        $app->notFound();
                    }
                })->via('GET');
        }

    }
}


