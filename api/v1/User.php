<?php

namespace Api;


class User extends ApiAbstract{

    public function execute($params){
        $storage = new \Api\Authorization\Pdo(array(), true);
        $user = new \Api\Authorization\User($storage);

        echo $user->createAccessToken(
            $this->app->request()->post('user_id'),
            $this->app->request()->post('name')
        );

        var_dump($storage->getUsers());

        //var_dump($params);

    }

    public function test($params){
        echo "Test success!";
        var_dump($params);
    }

}
