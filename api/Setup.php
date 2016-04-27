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
 */
return [
    ['pattern'=>'/user/:id/:name', 'object' => '\Api\ApiAbstract'],
    ['pattern'=>'/oauth', 'object' => '\Api\Oauth', 'function'=>'', 'methods'=>['GET','POST']],
    ['pattern'=>'/oauth/client/add', 'object' => '\Api\Oauth', 'function'=>'createClient', 'methods'=>['GET','POST']],
    ['pattern'=>'/user/add', 'object' => '\Api\User', 'function'=>'', 'methods'=>['GET','POST']],
    ['pattern'=>'/user/:id/order/:o_id', 'object' => '\Api\User', 'function'=>'test', 'methods'=>['GET','POST']],

];
