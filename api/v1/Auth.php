<?php

namespace Api;

use \OAuth2\ResponseType\AccessToken;
//use \OAuth2\Storage\Pdo;

class Auth extends ApiAbstract{

    public function createClient(){
        $storage = new \Api\Authorization\Pdo(array(), true);
        $client = new \Api\Authorization\Client($storage);
        echo $client->createAccessToken();
    }

}
