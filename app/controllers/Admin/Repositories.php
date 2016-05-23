<?php

namespace controllers\Admin;


class Repositories extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('Repositories');

        $content = new \View\Admin\Repositories();
        $content->reference('content', 'repositories');

        /*$viewHead = $this->getView()->getChild('head');
        $viewHead->addCss('admin/repositories.css');*/

        $this->renderView();
    }

    protected function _roleName(){
        return 'Repositories';
    }
}

