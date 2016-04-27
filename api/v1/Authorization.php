<?php

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

        //$pdo = new \PDO('sqlite:'.BP.DS.'api'.DS.'storage'.DS.'oauth.sqlite');
        $storage = new \Api\Authorization\Pdo(array(), true);

        //$storage->reInstallDb();
        //echo 'install db';
        //$this->next->call();
        //return;

        $message = '';
        $error = false;

        //validate request Auth_type
        /*if(!$this->request->headers('Auth_type') && !$this->request->request('Auth_type')){
            $message = 'Missing parameters: Auth_type in body or header';
            return $this->sendResponse($message, 400, true);
        }*/

        $header = getallheaders();
        $auth_type = isset($header['Auth_type'])? $header['Auth_type']:'';
        if($this->request->request('Auth_type')){
            $auth_type = $this->request->request('Auth_type');
        }
        //validate Auth_type
        if($auth_type && !in_array($auth_type, array('user', 'client'))){
            $message = 'Missing parameters: Auth_type not found.';
            return $this->sendResponse($message, 400, true);
        }

        if(!$auth_type || $auth_type == 'user'){
            $authoriation = new \Api\Authorization\User($storage);
        }elseif($auth_type == 'client'){
            $authoriation = new \Api\Authorization\Client($storage);
        }

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

