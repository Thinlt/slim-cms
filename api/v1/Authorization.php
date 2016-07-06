<?php

/**
 * API supported two method authorization those are Auth_type = client | user, default client
 * Auth_type is header key value or request body key value
 * Authorization is header key value or request body key value and value is
 * token <token_string> ext: "token e5fb64ab8fd069f846805fb004008472bd758407"
 */

namespace Api;

//Middleware
class Authorization extends \Slim\Middleware {

    protected $request;
    protected $response;

    public function call()
    {
        // Get reference to application
        $app = $this->app;
        $this->request = \OAuth2\Request::createFromGlobals();
        $this->response = $app->response();

        $storage = new \Api\Authorization\Pdo(array(), true);

        //$storage->reInstallDb();
        //echo 'install db';
        //$this->next->call();
        //return;

        //$header = getallheaders();
        $auth_type = isset($header['Auth_type'])? $header['Auth_type']:'';
        $authoriation = new \Api\Authorization\Type\Client($storage);

        if($authoriation->validate($this->request, $this->response)){
            $this->next->call();
        }else{
            $this->sendResponse($authoriation->getMessage(), 401, true);
        }
    }

    protected function sendResponse($message, $status, $error = 'false', $doc_link = ''){
        $this->response->header('Content-Type', 'application/json; charset=utf-8');
        $this->response->setStatus($status);
        $this->response->setBody(
            json_encode(array(
                'error' => $error,
                'message' => $message,
                'documentation_url' => $doc_link
            ), JSON_PRETTY_PRINT)
        );
        return $this;
    }
}

