<?php

namespace Api\Authorization;

use \OAuth2\RequestInterface;
use \Slim\Http\Response;

interface GrantTypeInterface {

    public function validate(RequestInterface $request, Response $response);

    public function getMessage();

}

