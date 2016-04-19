<?php

namespace Router;

class Frontend {

    public function dispatch($path, $name, $action, $app){
        //To do: do something
        $className = 'controllers\\'.ucfirst($path).'\\'.ucfirst($name).'\\'.ucfirst($action);

        if(!class_exists($className)){
            return $app->notFound();
        }
        $controller = new $className();
        $controller->execute($app);

    }
}


