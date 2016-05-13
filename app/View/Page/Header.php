<?php

namespace View\Page;

class Header extends \View\View {

    public function __construct()
    {
        parent::__construct();
    }

    protected function _init()
    {
        $this->addChild('header-top', new \View\Html\HeaderTop());
        $this->addChild('nav-header', new \View\Html\NavHeader());
    }
}


