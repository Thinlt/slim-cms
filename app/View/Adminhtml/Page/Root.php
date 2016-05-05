<?php

namespace View\Adminhtml\Page;

class Root extends \View\Page\Root {

    protected function _initChild()
    {
        //$this->setTemplate('adminhtml/page/root.html');

        $this->addChild('head', new \View\Adminhtml\Page\Head());
        $this->addChild('header', new \View\Adminhtml\Page\Header());
        $this->addChild('content', new \View\Adminhtml\Page\Content());
        $this->addChild('footer', new \View\Adminhtml\Page\Footer());
    }

    protected function _beforeToHtml(){
        //$this->setTemplate('adminhtml/page/root.html');
    }
}


