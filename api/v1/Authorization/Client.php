<?php

namespace Api\Authorization;

use \OAuth2\RequestInterface;
use \Slim\Http\Response;

class Client implements GrantTypeInterface {

    public $storage;
    public $authorization;
    public $message;

    public function __construct(\Api\Authorization\Pdo $storage)
    {
        $this->storage = $storage;
    }

    public function createAccessToken()
    {
        $access_token = $this->storage->generateAccessToken();
        $this->storage->addClient($access_token);
        $this->storage->setScope($access_token, $this->getScope());
        return $access_token;
    }

    public function getScope()
    {
        return 'client';
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
        $dataPareKey = '';
        $dataPare = array();
        $v = 1;
        foreach($authData as $data){
            if($v == 1){
                $dataPareKey = trim($data);
            }
            if($v == 2){
                $dataPare[$dataPareKey] = trim($data);
            }
            if($v == 1) $v = 2;
        }
        if(isset($dataPare['token'])){
            if($this->getAccessToken($dataPare['token'])){
                return true;
            }
        }
        return false;
    }

    public function getAccessToken($oauth_token)
    {
        return $this->storage->getClientByToken($oauth_token);
    }

}

