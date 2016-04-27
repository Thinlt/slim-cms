<?php

namespace Api\OAuth\GrantType;

use \OAuth2\GrantType\GrantTypeInterface;
use OAuth2\ResponseType\AccessTokenInterface;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;

class Client implements GrantTypeInterface {

    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope)
    {
        // TODO: Implement createAccessToken() method.
    }

    public function getClientId()
    {
        // TODO: Implement getClientId() method.
    }

    public function getQuerystringIdentifier()
    {
        return 'client';
    }

    public function getScope()
    {
        // TODO: Implement getScope() method.
    }

    public function getUserId()
    {
        // TODO: Implement getUserId() method.
    }

    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement validateRequest() method.
    }



}

