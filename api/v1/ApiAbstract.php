<?php

namespace Api;

class ApiAbstract {

    protected $app; //\Slim\Slim instance

    protected $responseData = array(
        'message'=>'No data'
    );

    public function __construct(\App $app){
        $this->app = $app;
    }

    /**
     * @param $params array( param1[, param2, ...], 'app' )
     * example requested: format is /user/:id/order/:order_id and request /user/10/order/5 -> params: id=10, order_id = 5
     */
    public function execute($params){
        $this->sendResponse();
    }

    protected function sendResponse($data = null){
        if($data){
            $this->responseData = $data;
        }
        if(!$this->app){
            $this->app = \App::getInstance();
        }
        $this->app->response->setBody(json_encode($this->responseData, JSON_PRETTY_PRINT));
        $this->app->response->header('Content-Type', 'application/json; charset=utf-8');
        //echo $this->app->response->getBody();
    }
}
