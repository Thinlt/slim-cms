<?php

namespace View\Admin\Account;

class Users extends \View\Adminhtml\ViewAbstract {


    protected function _init()
    {
        $this->reference('head')->addCss('admin/account-user.css');
        $this->setTemplate('admin/account/user/list.html');
    }

}


