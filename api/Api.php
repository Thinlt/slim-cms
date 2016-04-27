<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/22/2016
 * Time: 4:23 PM
 */
Class Api {

    protected $app;

    protected $version = '1';

    protected static $apis = array();


    public function __construct(\App $app = null)
    {
        $this->app = $app;
    }


    protected function authorize(){
        return true;
    }

    public function setVersion($v){
        $this->version = $v;
    }

    public function add($pattern, $object, $function = 'execute', $methods = array('GET')){
        if($function == null){
            $function = 'execute';
        }
        if($methods == null){
            $methods = 'GET';
        }
        array_push(static::$apis, array('pattern'=>$pattern, 'object'=>$object, 'function'=>$function, 'methods'=>$methods));
        return $this;
    }

    public function run($app = null){
        if($app){
            $this->app = $app;
        }
        foreach (static::$apis as $api) {
            if(!is_object($api['object']) && is_string($api['object'])){
                if(!class_exists($api['object'])){
                    throw new Exception('Class name '. $api['object'] .' does not exists');
                }
            }
            if(!is_object($api['object']) && !is_string($api['object'])){
                throw new Exception('Can not run api with non object '. $api['object']);
            }

            //$methods = array();
            if(!is_array($api['methods'])){
                $methods = array(strtoupper($api['methods']));
            }else{
                $methods = $api['methods'];
            }
            $app = $this->app;
            $app->map($api['pattern'], function() use ($api, $app){
                $params = func_get_args();
                $paramNames = array();
                preg_match_all('#:([\w]+)\+?#', str_replace(')', ')?', (string)$api['pattern']), $paramNames);
                $paramNames = $paramNames[1];
                //remap param names and values
                $paramsMapped = array();
                for($i = 0; $i<count($paramNames); $i++){
                    $paramsMapped[$paramNames[$i]] = $params[$i];
                }
                $params = $paramsMapped;
                $params['app'] = $app;
                if(is_string($api['object'])){
                    $objectName = $api['object'];
                    $callable = new $objectName($app);
                }else{
                    $callable = $api['object'];
                }
                call_user_func_array(array($callable, $api['function']), array($params));
            })->via($methods);
        }

        //Allway go to not found
        /*$app->map($this->app->request->getResourceUri(), function() use ($api, $app){
            $app->response->setBody(json_encode(array(
                'error' => 'true',
                'message' => 'Not Found',
                "documentation_url" => urlencode('https://www.google.com/')
            ), JSON_PRETTY_PRINT));
            $app->response->header('Content-Type', 'application/json; charset=utf-8');
        })->via('ANY');*/

        $app->notFound(function() use ($app) {
            $app->response->setBody(json_encode(array(
                'error' => 'true',
                'message' => 'Not Found',
                "documentation_url" => urlencode('https://www.google.com/')
            ), JSON_PRETTY_PRINT));
            $app->response->header('Content-Type', 'application/json; charset=utf-8');
            echo $app->response->getBody();
        });

    }

    public function setup($setups){
        foreach ($setups as $setup) {
            if(isset($setup['pattern']) && isset($setup['object'])){
                if(!isset($setup['function'])){
                    $setup['function'] = null;
                }
                if(!isset($setup['methods'])){
                    $setup['methods'] = 'GET';
                }
                $this->add($setup['pattern'], $setup['object'], $setup['function'], $setup['methods']);
            }
        }
    }

}