<?php

namespace controllers\Admin\Repositories;


class Remove extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            'repos' => array(),
            'message' => ''
        );

        $_POST = json_decode(file_get_contents('php://input'), true);

        $repo = new \Model\Repo();
        if(isset($_POST['id'])){
            $repo->load($_POST['id'])
                ->remove();
            $res['message'] = 'Remove repository success!';
            $res['success'] = true;
        }else{
            $res['message'] = 'No repository ID posted to server!';
        }
        //add releases to data
        $data = $repo->getCollection()->load();
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
        return 'Repositories.Remove';
    }
}

