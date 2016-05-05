<?php

namespace View\Admin;

class Login extends \View\View {

    public function __construct()
    {
        parent::__construct();
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


