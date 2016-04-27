<?php


namespace Controller;


abstract class ControllerAbstract implements \Controller\ControllerInterface {

    public $app;
    public $view;
    public $title;

    public function __construct()
    {

    }

    abstract function execute($app);

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
}

