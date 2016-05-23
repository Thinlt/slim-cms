<?php

namespace View\Admin\Account\View;

class Packages extends \View\Adminhtml\ViewAbstract {


    protected function _init()
    {
        $this->reference('head')->addCss('admin/account-user-view.css');
        $this->setTemplate('admin/account/user/view-packages.html');
    }

    /*protected function _beforeToHtml()
    {
        var_dump($this->getData());die;
    }*/

}


