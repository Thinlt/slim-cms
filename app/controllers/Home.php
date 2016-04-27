<?php

namespace controllers;


class Home extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        // TODO: Implement execute() method.
        $this->loadView();

        $this->setTitle('Home Page');



        $navheader = new \View\Html\NavHeader();
        $navheader->reference('header', 'nav-header');

        $head = $this->view->getChild('head');


        //$this->view->remove('footer');

        $home = new \View\Page\Home();
        $home->reference('content', 'home');

        $home1 = new \View\Page\Home1();
        $home1->reference('content', 'home1', 'before');

        $home2 = new \View\Page\Home2();
        $home2->reference('content', 'home2', 'after', 'home');

        $home2->reference('content', 'home2', 'after', 'home1');
        //$home1->reference('content', 'home1', 'before', 'home');

        $this->renderView();
        //Capitalize response body
        $res = $app->response;
        $body = $res->getBody();
        $res->setBody($body);
    }
}

