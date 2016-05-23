<?php

namespace controllers\Admin\Account;


class Users extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('User Account');

        $content = new \View\Admin\Account\Users();
        $content->reference('content', 'package.user');

        $this->renderView();
    }

    protected function _roleName(){
        return 'account.users';
    }
}

