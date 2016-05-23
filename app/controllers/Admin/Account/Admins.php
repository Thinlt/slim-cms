<?php

namespace controllers\Admin\Account;


class Admins extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('Admin Account');

        $content = new \View\Admin\Dashboard();
        $content->reference('content', 'dashboard');

        $this->renderView();
    }

    protected function _roleName(){
        return 'account.admins';
    }
}

