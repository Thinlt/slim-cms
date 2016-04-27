<?php

namespace Router;

class Frontend {

    public $_app;

    public function dispatch($rpath, $app){
        $this->_app = $app;
        //To do: do something
        $className = $this->exportControllerClass($rpath);
        if(!class_exists($className)){
            return $app->notFound();
        }
        $controller = new $className();
        $controller->setApp($app);
        $controller->execute($app);

    }

    public function exportControllerClass($rpath){
        $controllerPath = explode('/', trim($rpath, '/'));
        if(!isset($controllerPath[0])){
            if(!$this->_app){
                $this->_app = \App::getInstance();
            }
            $this->_app->notFound();
        }
        $className = 'controllers\\'.ucfirst($controllerPath[0]);
        if(!isset($controllerPath[1])){
            $className .= '\\Index';
        }
        if(!isset($controllerPath[2])){
            $className .= '\\Index';
        }

        if(isset($controllerPath[2])){
            $className .= '\\'.ucfirst($controllerPath[2]);
        }
        if(!class_exists($className)){
            $className = 'controllers\\'.ucfirst($controllerPath[0]);
            if(isset($controllerPath[1])){
                $className .= '\\'.ucfirst($controllerPath[1]);
            }
            if(isset($controllerPath[2])){
                $className .= '\\'.ucfirst($controllerPath[2]);
            }
        }
        return $className;
    }

    public function findRoute($route){
        $rpath = explode('/', trim($route, '/'));
        $className = 'controllers\\'.ucfirst($rpath[0]);
        if(isset($rpath[1])){
            $className .= '\\'.ucfirst($rpath[1]);
        }
        if(isset($rpath[2])){
            $className .= '\\'.ucfirst($rpath[2]);
        }
        if(!class_exists($className)){
            return false;
        }
        return true;
    }
}


