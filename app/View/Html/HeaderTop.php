<?php

namespace View\Html;

class HeaderTop extends \View\View {

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('html/header-top.html');
        $this->addChild('header-top-link', new \View\Html\HeaderTopLink());
    }


}


