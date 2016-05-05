<?php

namespace controllers;


class Admintest extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {

        die('abc def');



    }

    protected function _isAllow(){
        return true;
    }


}

