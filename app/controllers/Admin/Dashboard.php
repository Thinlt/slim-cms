<?php

namespace controllers\Admin;


class Dashboard extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {

        echo 'Welcom to Admin Dashboard';

        echo '<br/><a href="admin/logout" />Logout</a>';

    }


    protected function _roleName(){
        return 'Dashboard';
    }

    protected function _isCheckLogin(){
        return true;
    }
}

