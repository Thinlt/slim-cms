<?php

namespace View\Adminhtml\Page;

class Head extends \View\Page\Head {

    public function __construct()
    {
        parent::__construct();
        $this->addMeta(array('http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge'));
        $this->addMeta('viewport', 'width=device-width, initial-scale=1');

        $this->addCss('bootstrap.min.css');
        $this->addCss('slideout.css');
        $this->addCss('adminhtml/style.css');
        $this->addCss('//fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,vietnamese');
        $this->addCss('//fonts.googleapis.com/icon?family=Material+Icons');
        $this->addCss('//code.getmdl.io/1.1.3/material.indigo-pink.min.css');

        //$this->addJs('//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js');
        //$this->addJs('//ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js');
        //$this->addJs('//ajax.googleapis.com/ajax/libs/angular_material/1.0.7/angular-material.min.js');
        $this->addJs('bootstrap.min.js');
        $this->addJs('jquery.backstretch.min.js');
        $this->addJs('//code.getmdl.io/1.1.3/material.min.js');
        $this->addJs('slideout.min.js');
        $this->addJs('adminhtml/sidebar.js');
        $this->addJs('adminhtml/main.js');

    }



}


