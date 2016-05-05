<?php

namespace controllers\Admin;


class Login extends \Controller\Admin\ControllerAbstract {
    /**
     * @param $app
     * @throws \Exception
     */
    public function execute($app)
    {
        $session = \Model\Admin\Session::getSingleton();
        if($session->isLoggedIn()){
            $this->redirect($this->getUrl('admin'));
            return;
        }

        $this->loadView(new \View\Adminhtml\Page\Root());
        $this->setTitle('Admin Login');

        $content = new \View\Admin\Login();
        $content->reference('content', 'login.form');

        $this->renderView();
    }


    protected function _isAllow(){
        return true;
    }

    protected function _isCheckLogin(){
        return false;
    }
}

