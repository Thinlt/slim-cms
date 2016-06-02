<?php

namespace Api\User;


class Add extends \Api\ApiAbstract{

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

        $postData = $this->app()->request()->post();

        if(!isset($postData['user_name'])){
            $json = array(
                'message' => 'User name is null.',
                'error' => true
            );
            $this->sendResponse($json, 203);
            return;
        }

        if(!isset($postData['fullname'])){
            $postData['fullname'] = 'No name';
        }

        if(isset($postData['packages']) && is_array($postData['packages'])){
            $repo = new \Model\Repo();
            foreach($postData['packages'] as $key => $repo_id){
                $repo->clean()->load($repo_id);
                if(!$repo->getId()){
                   unset($postData['packages'][$key]);
                }
            }
            $postData['packages'] = implode(',', $postData['packages']);
        }else{
            $postData['packages'] = '';
        }

        $user = new \Model\User();
        $user->load($postData['user_name'], 'user_name');
        if(!$user->getId()){
            $json = array(
                'message' => '',
                'error' => false
            );
            $user->newUser($postData['user_name'], '', $postData['fullname']);
            try{
                $repoToken = new \Model\Repo\Token();
                $repoToken->load($user->getId(), 'user_id');
                $repoToken->setData('token', $user->getData('token'));
                $repoToken->setData('user_id', $user->getId());
                $repoToken->setData('repo_ids', $postData['packages']);
                $repoToken->save();
                $json['user_id'] = $user->getId();
                $json['token']   = $user->getData('token');
                $json['packages'] = explode(',', $repoToken->getData('repo_ids'));
                $json['message'] = 'Add user success!';
                $json['success'] = true;
                $json['error'] = false;
            }catch(\Exception $e){
                $user->remove();
                $json['message'] = 'Add user failed! - Exception: '.$e->getMessage();
                $json['success'] = false;
                $json['error'] = true;
            };

            $this->sendResponse($json, 200);
            return;

        }else{
            $json = array(
                'message' => 'User name already existed!',
                'error' => true
            );
            $this->sendResponse($json, 203);
            return;
        }

        $json = array(
            'message' => 'Bad request!',
            'error' => true
        );
        $this->sendResponse($json, 203);
    }

}
