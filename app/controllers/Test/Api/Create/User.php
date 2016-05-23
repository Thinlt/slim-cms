<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 5/17/2016
 * Time: 9:49 PM
 */
namespace controllers\Test\Api\Create;


class User extends \Controller\ControllerAbstract {

    public function execute($app)
    {
        $storage = new \Api\Authorization\Pdo(array(), true);
        $user = new \Api\Authorization\User($storage);

        echo $user->createAccessToken(
            $this->app->request()->post('user_id'),
            $this->app->request()->post('name')
        );

        var_dump($storage->getUsers());

    }

}



