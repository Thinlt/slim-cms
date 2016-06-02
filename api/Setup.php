<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/22/2016
 * Time: 4:23 PM
 */

/**
 * Example:
 *  [
        ['pattern'=>'/user/:id/:name', 'object' => '\Api\Test', 'function'=>'functionName', 'methods'=>'GET'],
        ['pattern'=>'/user/:id/order/:o_id', 'object' => '\Api\User', 'function'=>'test', 'methods'=>['GET','POST']],
    ];
 *
 * If: pattern is '/user/:id/order/:o_id' and request uri is '/user/10/order/20'
 * then: in function($params){
 *      $params['id'];      // = 10;
 *      $params['o_id'];    // = 20
 * }
 */
return [
    ['pattern'=>'/composer/json/token/:token/', 'object' => '\Api\Composer\Json', 'methods'=>['POST', 'GET']],
    ['pattern'=>'/composer/json/user/:user/', 'object' => '\Api\Composer\Json', 'methods'=>['POST', 'GET']],
    ['pattern'=>'/user/add/', 'object' => '\Api\User\Add', 'methods'=>['POST']],
    ['pattern'=>'/user/view/:email/', 'object' => '\Api\User\View', 'methods'=>['GET']],


];
