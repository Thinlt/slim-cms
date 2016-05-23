<?php

namespace controllers\Admin\Repositories;


class Get extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            'repos' => array()
        );

        $repo = new \Model\Repo();
        $data = $repo->getCollection()->load();
        if(is_array($data) && isset($data['id'])){
            $data = array($data);
        }
        //add releases to data
        $newData = array();
        foreach ($data as $_repo) {
            $repoLoad = new \Model\Repo();
            $repoLoad->load($_repo['id']);
            $releasesLoad = $repoLoad->getReleases();
            $tempData = array(
                'id'        =>  $_repo['id'],
                'repo_url'  => $_repo['repo_url'],
                'releases'  => ''
            );
            foreach ($releasesLoad as $rl_item) {
                $tempData['releases'] .= $rl_item->getData('tag_name').', ';
            }
            $tempData['releases'] = trim($tempData['releases'], ', ');
            $newData[] = $tempData;
        }

        $res['repos'] = $newData;
        echo json_encode($res);
    }

    protected function _roleName(){
        return 'Repositories.Get';
    }
}

