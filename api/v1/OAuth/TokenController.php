<?php

namespace Api\OAuth;

use \OAuth2\ResponseType\AccessTokenInterface;
use \OAuth2\Storage\AccessTokenInterface as AccessTokenStorageInterface;
use \OAuth2\GrantType\GrantTypeInterface;
use \OAuth2\RequestInterface;
use \OAuth2\ResponseInterface;

class TokenController implements \OAuth2\Controller\TokenControllerInterface {

    protected $accessToken;
    protected $grantTypes;
    protected $clientStorage;

    public function __construct(AccessTokenInterface $accessToken, AccessTokenStorageInterface $clientStorage)
    {
        $this->accessToken = $accessToken;
        $this->clientStorage = $clientStorage;
    }

    public function grantAccessToken(RequestInterface $request, ResponseInterface $response)
    {
        if (strtolower($request->server('REQUEST_METHOD')) != 'post') {
            $response->setError(405, 'invalid_request', 'The request method must be POST when requesting an access token', '#section-3.2');
            $response->addHttpHeaders(array('Allow' => 'POST'));
            return null;
        }

        /**
         * Determine grant type from request
         * and validate the request for that grant type
         */
        if (!$grantTypeIdentifier = $request->request('grant_type')) {
            $grantTypeIdentifier = 'user_token';
            //$response->setError(400, 'invalid_request', 'The grant type was not specified in the request');
            //return null;
        }

        if (!isset($this->grantTypes[$grantTypeIdentifier])) {
            /* TODO: If this is an OAuth2 supported grant type that we have chosen not to implement, throw a 501 Not Implemented instead */
            $response->setError(400, 'unsupported_grant_type', sprintf('Grant type "%s" not supported', $grantTypeIdentifier));
            return null;
        }

        $grantType = $this->grantTypes[$grantTypeIdentifier];

        /**
         * Retrieve the grant type information from the request
         * The GrantTypeInterface object handles all validation
         * If the object is an instance of ClientAssertionTypeInterface,
         * That logic is handled here as well
         */
        if (!$grantType->validateRequest($request, $response)) {
            return null;
        }

        //$accessToken = $grantType->createAccessToken($this->accessToken, $clientId, $grantType->getUserId(), $requestedScope);

        //var_dump($accessToken);die;

        return true;
    }

    public function handleTokenRequest(RequestInterface $request, ResponseInterface $response)
    {
        if ($this->grantAccessToken($request, $response)) {
            // @see http://tools.ietf.org/html/rfc6749#section-5.1
            // server MUST disable caching in headers when tokens are involved
            $response->setStatusCode(200);
            //$response->addParameters($token);
            $response->addHttpHeaders(array('Cache-Control' => 'no-store', 'Pragma' => 'no-cache'));
        }
    }

    /**
     * addGrantType
     *
     * @param grantType - OAuth2\GrantTypeInterface
     * the grant type to add for the specified identifier
     * @param identifier - string
     * a string passed in as "grant_type" in the response that will call this grantType
     */
    public function addGrantType(GrantTypeInterface $grantType, $identifier = null)
    {
        if (is_null($identifier) || is_numeric($identifier)) {
            $identifier = $grantType->getQuerystringIdentifier();
        }

        $this->grantTypes[$identifier] = $grantType;
    }
}
