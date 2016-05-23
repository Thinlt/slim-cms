<?php

namespace controllers\Admin\Api;


class Clients extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('Admin Api Client');

        $content = new \View\Admin\Api\Client();
        $content->reference('content', 'repositories');

        /*$viewHead = $this->getView()->getChild('head');
        $viewHead->addCss('admin/repositories.css');*/

        $this->renderView();
    }

    protected function _roleName(){
        return 'Repositories';
    }
}

