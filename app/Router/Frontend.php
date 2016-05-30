<?php

namespace Router;

class Frontend {

    public $_app;

    /**
     * Automatic match controller file
     * @param $rpath
     * @param $app
     * @return mixed
     */
    public function dispatch($rpath, $app){
        $this->_app = $app;
        $className = $this->exportControllerClass($rpath);
        if(!class_exists($className)){
            return $app->notFound();
        }
        $controller = new $className();
        $controller->setApp($app);
        $controller->_run($app);
    }

    /**
     * Manual defind a controller
     * @param $app
     */
    public function mapRoute($app){
        $app->get('/packages/:token/packages.json', function($token) use ($app){
            $app->params = array('token' => $token);
            $action = new \controllers\Package\PackagesJson();
            $action->setApp($app);
            $action->_run($app);
        });

        $app->get('/packages/file/:owner/:repo/:version/:token', function($owner, $repo, $version, $token) use ($app){
            $app->params = array('owner'=>$owner, 'repo'=>$repo, 'version'=>$version, 'token' => $token);
            $action = new \controllers\Package\PackagesFile();
            $action->setApp($app);
            $action->_run($app);
        });
    }

    public function exportControllerClass($rpath){

        if(!$this->findRoute($rpath)){
            if(!$this->_app){
                $this->_app = \App::getInstance();
            }
            $this->_app->notFound();
        }

        $controllerPath = explode('/', trim($rpath, '/'));
        foreach($controllerPath as $key => $path){
            $controllerPath[$key] = ucfirst($path);
        }
        $classPath = implode('\\', $controllerPath);
        $className = 'controllers\\'.$classPath;
        if(!class_exists($className) && count($controllerPath) <= 2){
            $className .= '\\Index';
        }
        return $className;
    }

    public function findRoute($route){
        $arrayPaths = explode('/', trim($route, '/'));

        foreach($arrayPaths as $key => $path){
            $arrayPaths[$key] = ucfirst($path);
        }

        $routePath = implode('\\', $arrayPaths);
        $className = 'controllers\\'.$routePath;
        if(!class_exists($className)){
            return false;
        }
        return true;
    }
}


