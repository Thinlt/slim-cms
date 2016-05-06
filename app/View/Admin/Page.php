<?php

namespace View\Admin;

class Page extends \View\Page\Root {

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('admin/page.html');
    }

    protected function _initChild(){
        $this->addChild('nav', new \View\Admin\Nav());
        $this->addChild('header', new \View\Adminhtml\Page\Header());
        $this->addChild('content', new \View\Adminhtml\Page\Content());
        $this->addChild('footer', new \View\Adminhtml\Page\Footer());
    }

    protected function _beforeToHtml()
    {
        $headerNavTop = new \View\Admin\NavTop();
        $headerNavTop->reference('header', 'nav_top');
    }

    public function getNavHtml(){
        if($this->getChild('nav')){
            return $this->getChild('nav')->getHtml();
        }
        return '';
    }


}


