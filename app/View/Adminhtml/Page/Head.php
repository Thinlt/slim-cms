<?php

namespace View\Adminhtml\Page;

class Head extends \View\Page\Head {

    public function __construct()
    {
        parent::__construct();

    }

    protected function _init()
    {
        $this->addMeta(array('http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge'));
        $this->addMeta('viewport', 'width=device-width, initial-scale=1');

        $this->addCss('bootstrap.min.css');
        $this->addCss('//fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,vietnamese');
        $this->addCss('//fonts.googleapis.com/icon?family=Material+Icons');
        $this->addCss('//code.getmdl.io/1.1.3/material.indigo-pink.min.css');
        $this->addCss('mui.min.css');
        $this->addCss('font-awesome.min.css');
        //$this->addCss('slideout.css');
        $this->addCss('adminhtml/style.css');
        $this->addCss('adminhtml/sidebar.css');
        $this->addCss('adminhtml/header.css');
        $this->addCss('adminhtml/style-media-min-width-768px.css');
        $this->addCss('adminhtml/style-media-max-width-768px.css');

//        $this->addJs('//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.js');
        $this->addJs('bootstrap.min.js');
        $this->addJs('jquery.backstretch.min.js');
        $this->addJs('//code.getmdl.io/1.1.3/material.min.js');
        $this->addJs('slideout.min.js');
        $this->addJs('adminhtml/sidebar.js');
        $this->addJs('adminhtml/main.js');
        $this->addJs('mui.min.js');
    }


}


