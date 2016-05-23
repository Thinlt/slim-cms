<?php

namespace View\Adminhtml\Page;

class Root extends \View\Page\Root {

    protected function _initChild()
    {
        $this->addChild('head', new \View\Adminhtml\Page\Head());
        $this->addChild('body', new \View\Admin\Page());
        $this->addChild('before_body_start', new \View\Adminhtml\Page\BeforeBodyStart());
        $this->addChild('after_body_start', new \View\Adminhtml\Page\AfterBodyStart());
    }

    protected function _beforeToHtml(){
        //$this->setTemplate('adminhtml/page/root.html');
    }

    public function getBodyHtml(){
        if($this->getChild('body')){
            return $this->getChild('body')->getHtml();
        }
        return '';
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

    public function getPageClass(){
        $head = $this->getChild('head');
        $title = strtolower($head->getTitle());
        $title = preg_replace('/\s+/', '-', $title);
        return $title;
    }
}


