<?php

namespace Router;

class Backend {

    public function dispatch($path, $name, $action, $app){
        //To do: do something
        $className = 'controllers\\'.ucfirst($path).'\\'.ucfirst($name).'\\'.ucfirst($action);

        if(!class_exists($className)){
            $app->notFound(function () use ($app) {
                $app->render('page/404.html');
            });
        }
        $controller = new $className();
        $controller->execute($app);

    }
}


