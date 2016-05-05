<?php

namespace controllers\Admin;


class Accessdenied extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        die('access denied');
    }
}

