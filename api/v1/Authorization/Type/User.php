<?php

namespace Api\Authorization\Type;

use \OAuth2\RequestInterface;
use \Slim\Http\Response;

class User implements \Api\Authorization\GrantTypeInterface {

    public $storage;
    public $authorization;
    public $message;

    public function __construct(\Api\Authorization\Pdo $storage)
    {
        $this->storage = $storage;
    }

    public function createAccessToken($user_id, $user_name = null)
    {
        $access_token = $this->storage->generateAccessToken();
        $this->storage->addUser($user_id, $access_token, $user_name);
        $this->storage->setScope($access_token, $this->getScope());
        return $access_token;
    }

    public function getScope()
    {
        return 'user';
    }

    public function validate(RequestInterface $request, Response $response)
    {
        if(!$request->headers('Authorization') && !$request->request('Authorization')){
            $this->message = 'Missing parameter: "Authorization" is required';
            return false;
        }
        $authorization = $request->headers('Authorization');
        if($request->request('Authorization')){
            $authorization = $request->request('Authorization');
        }
        $this->authorization = $authorization;

        if($this->isTokenValidated()){
            return true;
        }
        $this->message = 'Token validate failed.';
        return false;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function isTokenValidated(){
        $authData = explode(' ', $this->authorization);
        $dataPare = array();
        list($dataPareKey, $dataValue) = $authData;
        $dataPare[$dataPareKey] = trim($dataValue);
        if(isset($dataPare['token'])){
            if($this->getAccessToken($dataPare['token'])){
                return true;
            }
        }
        return false;
    }

    public function getAccessToken($oauth_token)
    {
        return $this->storage->getUserByToken($oauth_token);
    }

    public function setAccessToken($oauth_token, $user_id)
    {
        return $this->storage->setUser($user_id, $oauth_token);
    }
}

