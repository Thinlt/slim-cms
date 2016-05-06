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
        $this->addChild('before_body_start', new \View\Adminhtml\Page\BeforeBodyStart());
        $this->addChild('after_body_start', new \View\Adminhtml\Page\AfterBodyStart());
    }

    protected function _beforeToHtml(){
        //$this->setTemplate('adminhtml/page/root.html');
    }

    public function getBeforeBodyStartHtml(){
        if($this->getChild('before_body_start')){
            return $this->getChild('before_body_start')->getHtml();
        }
        return '';
    }

    public function getAfterBodyStartHtml(){
        if($this->getChild('after_body_start')){
            return $this->getChild('after_body_start')->getHtml();
        }
        return '';
    }
}


