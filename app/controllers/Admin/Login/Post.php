<?php

namespace controllers\Admin\Login;


class Post extends \Controller\Admin\ControllerAbstract {
    /**
     * @param $app
     * @throws \Exception
     */
    public function execute($app)
    {
        $user = new \Model\Admin\User();
        $res = $user->login($app->request()->params('user_name'), $app->request()->params('password'));
        if($res){
            $this->redirect($this->getUrl('admin/dashboard'));
        }else{
            $session = \Model\Admin\Session::getSingleton();
            $session->addError('Login failed: not correct user name or password!');
            $this->redirect($this->getUrl('admin/login'));
        }
    }


    protected function _isAllow(){
        return true;
    }

    protected function _isCheckLogin(){
        return false;
    }
}

