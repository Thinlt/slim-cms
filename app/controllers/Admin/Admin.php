<?php

namespace controllers\Admin;


class Admin extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {

        echo 'Admin controller';

        echo '<br/><a href="/admin/logout" />Logout</a>';

    }


}

