<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 5/17/2016
 * Time: 9:49 PM
 */
namespace controllers\Test\Api\Create;


class Client extends \Controller\ControllerAbstract {

    public function execute($app)
    {
        $storage = new \Api\Authorization\Pdo(array(), true);
        $client = new \Api\Authorization\Client($storage);
        echo $client->createAccessToken();
    }

}



