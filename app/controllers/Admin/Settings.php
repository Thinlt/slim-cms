<?php

namespace controllers\Admin;


class Settings extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('Admin Settings');

        $content = new \View\Admin\Settings();
        $content->reference('content', 'settings');

        $this->renderView();
    }

    protected function _roleName(){
        return 'Settings';
    }

}

