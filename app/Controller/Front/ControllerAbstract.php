<?php

namespace Controller\Front;


class ControllerAbstract extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        die('Not found: controller front abstract');
    }

    protected function _beforeExecute($app)
    {
        $session = \Model\User\Session::getSingleton();
        $app->setSession($session);
        if(!$this->_isAllow()){
            $session->setError('Permission denied');
            $request = $app->request();
            if($request->getReferrer()){
                $url = $request->getReferrer();
            }else{
                $url = $request->getUrl().'/admin/accessdenied';
            }
            if($url){
                $this->redirect($url);
            }else{
                throw new \Model\Exception\Denied('Permission denied');
            }
        }
    }

    protected function getAllow(){
        return 'Front';
    }

    protected function _isAllow(){
        if($this->app){
            $session = $this->app->getSession();
            if($session && $session->getUser()){
                $role = $session->getUser()->getRole();
                if($role->checkAllow($this->getAllow())){
                    return true;
                };
            }
        }
        return false;
    }

}

