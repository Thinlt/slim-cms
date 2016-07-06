<?php

namespace Api\Authorization\Type;

use \OAuth2\RequestInterface;
use \Slim\Http\Response;

interface TypeInterface {

    public function validate(RequestInterface $request, Response $response);

    public function getMessage();

}

