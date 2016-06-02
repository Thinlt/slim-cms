<?php

namespace Api\User;


class View extends \Api\ApiAbstract{

    /**
     * Method: POST api/user/add
     * Post data: {user_name: '<email>', fullname: '<string>', packages: [1, 2, 3, ...]}
     * Response data:
     * {
     *      user_id: <int>,
     *      token: <string>,
     *      packages: []
     * }
     * @param $params
     */
    public function execute($params){

        if(!isset($params['email'])){
            $json = array(
                'message' => 'No user name param.',
                'error' => true
            );
            $this->sendResponse($json, 203);
            return;
        }

        $user = new \Model\User();
        $user->load($params['email'], 'user_name');

        $json = array(
            'message' => '',
            'error' => true
        );

        if($user->getId()){
            $json = array_merge($json, $user->getData());
            $json['message'] = 'Success!';
            $json['error'] = false;
        }else{
            $json['message'] = 'User '.$params['email'].' not found!';
            $json['error'] = true;
        }
        $this->sendResponse($json, 200);
    }

}
