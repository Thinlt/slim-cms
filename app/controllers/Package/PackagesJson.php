<?php

namespace controllers\Package;

/**
 * Request url: packages/<token>/packages.json
 * Class PackagesJson
 * @package controllers\Package
 */

/**
 * Example: /packages/d0e39be13c309177ebf3363a416f7615483271e6/packages.json
 */

class PackagesJson extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();

        if(isset($app->params['token'])){
            $token = new \Model\Repo\Token();
            $token->load($app->params['token'], 'token');
            if($token->getId()){
                $repo_ids = $token->getData('repo_ids');
                if($repo_ids){
                    $repo = new \Model\Repo();
                    $repo->getCollection()
                        ->where('id IN ('.trim($repo_ids, ',').')');
                    $repos = $repo->loadCollection();
                    $packages = array();
                    foreach($repos as $repo){

                        //load releases
                        $release = new \Model\Repo\Release();
                        $release->getCollection()
                            ->where(sprintf('repo_id = %s', $repo->getId()));
                        $releaseCollection = $release->loadCollection();
                        $releases = array();
                        foreach($releaseCollection as $release){

                            $version_normalized = $release->getData('tag_name');
                            if(count(explode('.', $version_normalized)) == 3){
                                $version_normalized .= '.0';
                            }

                            $releases[$release->getData('tag_name')] = array(
                                'name' => strtolower($repo->getData('owner').'/'.$repo->getData('repo')),
                                'version' => $release->getData('tag_name'),
                                'version_normalized' => $version_normalized,
                                'dist'  => array(
                                    'type' => 'zip',
                                    'url'  => $app->view()->getUrl(
                                        sprintf('packages/file/%s/%s/%s/%s',
                                            $repo->getData('owner'),
                                            $repo->getData('repo'),
                                            $version_normalized,
                                            $token->getData('token'))),
                                    'reference' => '',
                                    'shasum' => ''
                                ),
                                'require' => array(
                                    'php' => '>=5.3.0'
                                ),
                                'time'  => '',
                                'type' => 'library',
                                'description' => $release->getData('body')
                            );
                        }

                        $packages[strtolower($repo->getData('owner').'/'.$repo->getData('repo'))] = $releases;
                    }

                    $json = array(
                        'packages' => $packages
                    );

                    $json =  json_encode($json, JSON_PRETTY_PRINT);
                    echo str_replace('\\', '', $json);

                    $app->response()->setStatus(200);
                    return;
                }
            }
        }

        $app->response()->setStatus(403);


        /*{
            "packages": {
            "magestore/intergration": {
                "1.0.0": {
                    "name": "magestore/intergration",
                    "version": "1.0.0",
                    "version_normalized": "1.0.0.0",
                    "source": {
                        "type": "git",
                        "url": "git@github.com:Magestore/Integration.git",
                        "reference": "3b6e37bcf6f974c0950242a0d9fc0bd0662a21e3"
                    },
                    "dist": {
                        "type": "zip",
                        "url": "http://demo-magento2.magestore.com/tit/test-2/intergration.php?token=123",
                        "reference":"",
                        "shasum": ""
                    },
                    "require": {
                        "php": ">=5.3.0"
                    },
                    "time": "2016-04-06 04:48:29",
                    "type": "library",
                    "description": "Magestore Intergration",
                    "support": {
                        "source": "https://github.com/Magestore/Integration/tree/1.0.0",
                        "issues": "https://github.com/Magestore/Integration/issues"
                    }
                }
            }
        }*/

    }
}

