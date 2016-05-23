<?php

namespace View\Admin\Account;

class Admins extends \View\Adminhtml\ViewAbstract {


    protected function _init()
    {
        $this->reference('head')->addCss('admin/repositories.css');
        $this->setTemplate('admin/account/admin/list.html');
    }

}


