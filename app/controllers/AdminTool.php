<?php

namespace controllers;


class AdminTool extends \Controller\ControllerAbstract {
    public function execute($app)
    {

        //add role
        try{
            $role = new \Model\Admin\Role();
            //$role->newRole('Admin', array('sources'=>'all'));
            var_dump($role->getCollection()->load());
        }catch(\Exception $e){
            die($e->getMessage());
        }

echo '<br/>';
        //add user
        $user = new \Model\Admin\User();
//        $user->newUser('admin', 'abc123@', 'TIT '.($user->getCollection()->getSize()+1));
        echo '<br/>';
        var_dump($user->getCollection()->load());


    }


}

