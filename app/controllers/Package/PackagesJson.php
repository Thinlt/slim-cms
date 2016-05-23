<?php

namespace controllers\Package;


class PackagesJson extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        echo $app->params['token'];
        //var_dump($app);
    }
}

