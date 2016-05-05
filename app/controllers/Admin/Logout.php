<?php

namespace controllers\Admin;


class Logout extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $session = \Model\Admin\Session::getSingleton();
        $session->logout();
        $this->redirect('admin/login');
    }
}

