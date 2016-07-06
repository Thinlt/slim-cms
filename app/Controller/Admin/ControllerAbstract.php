<?php

namespace Controller\Admin;


class ControllerAbstract extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        die('Not found: controller admin abstract');
    }

    public function loadView($view = null){
        if($view){
            $this->view = $view;
            $this->app->view($this->view);
        }
        if(!$this->view){
            $root = new \View\Adminhtml\Page\Root();
            $this->view = $root;
            $this->app->view($this->view); //set root view for app
        }
        return $this;
    }


    /**
     * Check allow logged in
     * @param $app
     * @throws \Model\Exception\Denied
     */
    protected function _beforeExecute($app)
    {
        $session = \Model\Admin\Session::getSingleton();
        //$session->init('admin');
        $app->setSession($session);
        if($this->_isCheckLogin()){
            $request = $app->request();
            if(!$session->isLoggedIn()){
                $this->redirect($app->getUrl('admin/login'));
            }
            if(!$this->_isAllow()){
                $session->setError('Permission denied');
                if($request->getReferrer()){
                    $url = $request->getReferrer();
                }else{
                    $url = $app->getUrl('admin/accessdenied');
                }
                if($url){
                    $this->redirect($url);
                }else{
                    throw new \Model\Exception\Denied('Permission denied');
                }
            }
        }
    }


    protected function _isAllow(){
        if($this->app){
            $session = $this->app->getSession();
            if($session && $session->getUser()){
                $role = $session->getUser()->getRole();
                if($role->checkAllow($this->_roleName())){
                    return true;
                };
            }
        }
        return false;
    }

    public function roleName(){
        return $this->_roleName();
    }

    protected function _roleName(){
        return 'Admin';
    }

    protected function _isCheckLogin(){
        return true;
    }

}

