<?php

namespace Api\OAuth\GrantType;

use \OAuth2\GrantType\GrantTypeInterface;
use \OAuth2\ResponseType\AccessTokenInterface;
use \OAuth2\Storage\AccessTokenInterface as AccessTokenStorageInterface;
use \OAuth2\RequestInterface;
use \OAuth2\ResponseInterface;

class User implements GrantTypeInterface {

    public $storage;
    public $authorization;

    public function __construct(AccessTokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope)
    {
        return $accessToken->createAccessToken($client_id, $user_id, $scope);
    }

    public function getClientId()
    {
        die('get client id');
        // TODO: Implement getClientId() method.
    }

    public function getQuerystringIdentifier()
    {
        return 'user_token';
    }

    public function getScope()
    {
        die('get scop');
    }

    public function getUserId()
    {
        die('get user id');
        // TODO: Implement getUserId() method.
    }

    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        if(!isset($request->headers['AUTHORIZATION']) && !$request->request('Authorization')){
            $response->setError(400, 'invalid_request', 'Missing parameters: Authorization in body or header');
            return null;
        }
        $authorization = $request->headers['AUTHORIZATION'];
        if($request->request('Authorization')){
            $authorization = $request->request('Authorization');
        }
        $this->authorization = $authorization;

        return true;
    }

    public function isTokenValidated(){
        $token = str_replace('token', '', $this->authorization);
        $token = trim($token);
        if(!$this->storage->getAccessToken($token)){
            return false;
        }
        return true;
    }

}

