<?php

namespace View\Html;

class NavHeader extends \View\View {

    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('html/nav-header.html');
    }


}


