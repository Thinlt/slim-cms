<?php

namespace controllers\Admin\Repositories;


class Update extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            //'repos' => array(),
            'message' => ''
        );

        $_POST = json_decode(file_get_contents('php://input'), true);

        $repo = new \Model\Repo();
        if(!isset($_POST['id'])){
            $res = array(
                'repos' => array(),
                'message' => 'error'
            );
        }else{
            $id = $_POST['id'];
            $repo->load($id);
            if($repo->getId()){
                $gitRepo = new \Model\Github\Repos();
                $releasesData = $gitRepo->releases($repo); //array
                if(!is_object($releasesData) && isset($releasesData[0]) && property_exists($releasesData[0], 'url')
                    && property_exists($releasesData[0], 'tag_name')){
                    $release = new \Model\Repo\Release();
                    //delete old release data
                    $release->getCollection()
                        ->where(array('repo_id'=>$repo->getId()));
                    $oldRelease = $release->loadCollection();
                    foreach($oldRelease as $oldRel){
                        $oldRel->remove();
                    }

                    foreach ($releasesData as $rdata) {
                        $release->clean()->setData(array(
                            'repo_id'       => $repo->getId(),
                            'release_id'    => $rdata->id,
                            'tag_name'      => $rdata->tag_name,
                            'name'          => $rdata->name,
                            'draft'         => $rdata->draft,
                            'prerelease'    => $rdata->prerelease,
                            'tarball_url'   => $rdata->tarball_url,
                            'zipball_url'   => $rdata->zipball_url,
                            'html_url'      => $rdata->html_url,
                            'url'           => $rdata->url,
                            'body'          => $rdata->body
                        ))->save();

                        try{
                            $gitRepo->download($rdata->zipball_url, $repo->getData('owner'), $repo->getData('repo'));
                        }catch(\Exception $e){

                        }
                    }
                }
                $res['message'] = 'Update repository success!';
                $res['success'] = true;
            }else{
                $res['message'] = 'Cannot load repository model!';
            }
        }

        /*$data = $repo->getCollection()->load();
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
        $res['repos'] = $newData;*/
        echo json_encode($res);
    }

    protected function _roleName(){
        return 'Repositories.Update';
    }
}

