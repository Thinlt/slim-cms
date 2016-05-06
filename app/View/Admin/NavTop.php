<?php

namespace View\Admin;

class NavTop extends \View\Adminhtml\ViewAbstract {

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('admin/nav-top.html');
    }


}


