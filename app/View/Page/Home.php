<?php

namespace View\Page;

class Home extends \View\View {

    public function __construct()
    {
        parent::__construct();
        $head = $this->reference('head');

        $head->addCss('bootstrap.min.css');
        $head->addCss('//fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,vietnamese');
        $head->addCss('//fonts.googleapis.com/icon?family=Material+Icons');
        //$head->addCss('//code.getmdl.io/1.1.3/material.indigo-pink.min.css');
        $head->addCss('mui.min.css');
        $head->addCss('font-awesome.min.css');
        $head->addCss('frontend/style.css');

        $head->addJs('bootstrap.min.js');
        $head->addJs('jquery.backstretch.min.js');
//        $head->addJs('//code.getmdl.io/1.1.3/material.min.js');
//        $head->addJs('slideout.min.js');
//        $head->addJs('mui.min.js');
    }


}


