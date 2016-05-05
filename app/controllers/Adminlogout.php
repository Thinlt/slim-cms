<?php

namespace controllers;


class Adminlogout extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        $session = \Model\Admin\Session::getSingleton();
        $session->logout();
        echo "success";
    }


}

