<?php

namespace controllers;


class Adminlogin extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        $session = \Model\Admin\Session::getSingleton();
        $res = $session->login('admin', 'abc123@');
        if($res){
            echo "success!";
            var_dump($res);
        }else{
            echo "failed";
        }

    }


}

