<?php

namespace View\Admin;

class Login extends \View\Adminhtml\Page\Root {

    protected function _initChild()
    {
        //$this->setTemplate('adminhtml/page/root.html');
        $this->addChild('head', new \View\Adminhtml\Page\Head());
        $this->addChild('header', new \View\Adminhtml\Page\Header());
        $this->addChild('footer', new \View\Adminhtml\Page\Footer());
    }

    public function getActionUrl(){
        return $this->getUrl('admin/login/post');
    }

    public function getMessage(){
        $session = \Model\Admin\Session::getSingleton();
        $messages = $session->getError();
        $html = '';
        if(is_array($messages)){
            foreach ($messages as $ms) {
                $html .= sprintf('<span>%s</span>', $ms).PHP_EOL;
            }
        }else{
            $html .= sprintf('<span>%s</span>', $messages);
        }
        return $html;
    }
}


