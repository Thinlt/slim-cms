<?php

namespace View\Adminhtml;

class ViewAbstract extends \View\View {

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


