<?php

namespace controllers\Admin\Account\Users;


class Get extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            'users' => array()
        );

        $user = new \Model\User();
        $data = $user->getCollection()->load();

        $res['users'] = $data;
        echo json_encode($res);
    }

    protected function _roleName(){
        return 'Account.Users.Get';
    }
}

