<?php


namespace Controller;


abstract class ControllerAbstract implements \Controller\ControllerInterface, \Controller\RoleInterface {

    public $app;
    public $view;
    public $title;

    protected static $roles = array(); //all role name stored as array

    public function __construct()
    {
        $this->_addGlobalRole();
    }

    abstract function execute($app);

    public function _run($app){
        $this->_beforeExecute($app);
        return $this->execute($app);
    }

    protected function _beforeExecute($app){
        return $this;
    }

    public function setApp($app){
        $this->app = $app;
        return $this;
    }

    public function loadView($view = null){
        if($view){
            $this->view = $view;
            $this->app->view($this->view);
        }
        if(!$this->view){
            $root = new \View\Page\Root();
            $this->view = $root;
            $this->app->view($this->view); //set root view for app
        }
        return $this;
    }

    public function renderView($view = null){
        if($view){
            $this->setView($view);
        }
        $view = ($this->view instanceOf \View\View) ? $this->view : new $this->view;
        return $view->display('');
    }

    public function setView($view){
        $this->view = $view;
        return $this->view;
    }

    public function getView(){
        if($this->view){
            return $this->view;
        }
        return \View\View::getSingleton();
    }

    public function render($view = null){
        return $this->renderView($view);
    }

    public function getRequest(){
        if($this->app){
            if($this->app->request){
                return $this->app->request;
            }
        }
        return new \Slim\Helper\Set();
    }

    public function setTitle($title){
        $this->title = $title;
        if($this->view){
            if($this->view->getChild('head')){
                $this->view->getChild('head')->setTitle($title);
            }
        }
        return $this;
    }

    public function redirect($url){
        if($this->app){
            $this->app->redirect($url, 200);
        }
    }

    public function getUrl($path = '', $params = array()){
        return $this->app->getUrl($path, $params);
    }

    public function roleName()
    {
        return '';
    }

    protected function _addGlobalRole(){
        if(!in_array($this->roleName(), static::$roles) && $this->roleName() != ''){
            array_push(static::$roles, $this->roleName());
        }
        return $this;
    }

    public function getAllRoles(){
        return static::$roles;
    }
}

