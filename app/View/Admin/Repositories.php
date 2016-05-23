<?php

namespace View\Admin;

class Repositories extends \View\Adminhtml\ViewAbstract {


    protected function _init()
    {
        $this->reference('head')->addCss('admin/repositories.css');
    }

}


