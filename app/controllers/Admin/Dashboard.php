<?php

namespace controllers\Admin;


class Dashboard extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('Admin Dashboard');

        $content = new \View\Admin\Dashboard();
        $content->reference('content', 'dashboard');

        $this->renderView();
    }

    protected function _roleName(){
        return 'Dashboard';
    }

}

