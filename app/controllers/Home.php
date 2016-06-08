<?php

namespace controllers;


class Home extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        // TODO: Implement execute() method.
        $this->loadView();
        $this->setTitle('Home Page');
        /*$navheader = new \View\Html\NavHeader();
        $navheader->reference('header', 'nav-header');*/
        $head = $this->view->getChild('head');

        $this->view->remove('header');
        $this->view->remove('footer');

        $home = new \View\Page\Home();
        $home->reference('content', 'home');

        $this->renderView();

    }
}

