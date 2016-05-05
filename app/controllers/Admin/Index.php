<?php

namespace controllers\Admin;


class Index extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {

        echo 'Welcom to Admin Dashboard index';

        echo '<br/><a href="admin/logout" />Logout</a>';

    }


    protected function _roleName(){
        return 'Dashboard';
    }

    protected function _isCheckLogin(){
        return true;
    }
}

