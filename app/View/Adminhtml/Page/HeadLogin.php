<?php

namespace View\Adminhtml\Page;

class HeadLogin extends \View\Page\Head {

    protected function _init()
    {
        $this->addMeta(array('http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge'));
        $this->addMeta('viewport', 'width=device-width, initial-scale=1');

        $this->addCss('bootstrap.min.css');
        $this->addCss('//fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,vietnamese');
        $this->addCss('//fonts.googleapis.com/icon?family=Material+Icons');
        $this->addCss('//code.getmdl.io/1.1.3/material.indigo-pink.min.css');
        $this->addCss('adminhtml/style.css');
        $this->addCss('adminhtml/login.css');

        $this->addJs('bootstrap.min.js');
        $this->addJs('jquery.backstretch.min.js');
        $this->addJs('//code.getmdl.io/1.1.3/material.min.js');
    }


}


