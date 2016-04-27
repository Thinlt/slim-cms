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
    ['pattern'=>'/auth', 'object' => '\Api\Auth', 'function'=>'', 'methods'=>['GET','POST']],
    ['pattern'=>'/auth/client/add', 'object' => '\Api\Auth', 'function'=>'createClient', 'methods'=>['GET','POST']],
    ['pattern'=>'/user/add', 'object' => '\Api\User', 'function'=>'', 'methods'=>['GET','POST']],
    ['pattern'=>'/user/:id/order/:o_id', 'object' => '\Api\User', 'function'=>'test', 'methods'=>['GET','POST']],

];