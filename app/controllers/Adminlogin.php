<?php

namespace controllers;


class Adminlogin extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        $user = new \Model\Admin\User();
        $res = $user->login('admin', 'abc123@');
        if($res){
            echo "success!";
        }else{
            echo "failed";
        }

    }


}

