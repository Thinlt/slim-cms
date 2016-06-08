<?php

namespace Api\User\Repo;


class Add extends \Api\ApiAbstract{

    /**
     * Method: POST api/user/{id}/repos/add
     * Post data:
     *    {repo_url: ['<http://github.com/_/_.git>']}
     * or {owner: '', repo: ''}
     * or {repos: [{owner: '', repo: ''}, ...]}
     * Response data:
     * {
     *      message: <string>,
     *      repo_ids: [<int>, ...],
     *      success: true | false
     * }
     * @param $params
     */
    public function execute($params){
        $repoIds = array();
        $postData = $this->app()->request()->post();
        if(isset($postData['repo_url'])){
            if(!is_array($postData['repo_url'])){
                $postData['repo_url'] = array($postData['repo_url']);
            }
            foreach($postData['repo_url'] as $rpurl){
                $repo = new \Model\Repo();
                $repo->load($rpurl, 'repo_url');
                if($repo->getId())
                    $repoIds[] = $repo->getId();
            }
        }elseif(isset($postData['owner']) && isset($postData['repo'])){
            $repo = new \Model\Repo();
            $repo->getCollection()
                ->where(array('owner'=>$postData['owner'], 'repo'=>$postData['repo']));
            $collection = $repo->loadCollection();
            if($collection){
                $repo = $collection[0];
            }
            if($repo->getId())
                $repoIds[] = $repo->getId();
        }elseif(isset($postData['repos']) && is_array($postData['repos'])){
            $ownerArr = $repoArr = array();
            foreach($postData['repos'] as $rp){
                if(isset($rp['owner']) && isset($rp['repo'])){
                    $ownerArr[] = $rp['owner'];
                    $repoArr[] = $rp['repo'];
                }
            }
            $repo = new \Model\Repo();
            $collection = $repo->getCollection()
                ->columns(array('id'))
                ->where('owner IN ("'.implode('", "', $ownerArr).'") AND repo IN ("'.implode('", "', $repoArr).'")');
            $collData = $collection->load();
            foreach($collData as $id){
                if($id['id'])
                    $repoIds[] = $id['id'];
            }
        }else{
            $json = array(
                'message' => 'Error request data',
                'error' => true
            );
            $this->sendResponse($json, 203);
            return;
        }

        $user = new \Model\User();
        $user->load($params['id']);
        if($user->getId()){
            $repoToken = new \Model\Repo\Token();
            $repoToken->load($user->getId(), 'user_id');
            if($repoToken->getId()){
                $repoToken->setData('token', $user->getData('token'));
                $repoToken->setData('user_id', $user->getId());
                $reppTokenRepoIds = explode(',', $repoToken->getData('repo_ids'));
                foreach($repoIds as $id){
                    if(!in_array($id, $reppTokenRepoIds)){
                        $reppTokenRepoIds[] = $id;
                    }
                }
                $repoIds = $reppTokenRepoIds;
                $repoToken->setData('repo_ids', implode(',', $repoIds));
                $repoToken->save();
            }else{
                $repoToken->setData('token', $user->getData('token'));
                $repoToken->setData('user_id', $user->getId());
                $repoToken->setData('repo_ids', implode(',', $repoIds));
                $repoToken->save();
            }
            $json = array(
                'message' => 'Success!',
                'repo_ids' => $repoIds,
                'error'   => false,
                'success' => true
            );
            $this->sendResponse($json, 200);
            return;
        }

        $json = array(
            'message' => 'Bad request!',
            'error' => true
        );
        $this->sendResponse($json, 203);
    }

}
