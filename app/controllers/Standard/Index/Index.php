<?php

namespace controllers\Standard\Index;


class Index extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        // TODO: Implement execute() method.
        //die('hehe');

        $this->loadView()
            ->renderView();

        //$app->view(new \View\Page\Root());

        //$app->render();

        //Capitalize response body
        $res = $app->response;
        $body = $res->getBody();
        $res->setBody($body);
    }
}

