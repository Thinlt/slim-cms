<?php

namespace controllers\Package;

/**
 * Request url: packages/file/<owner>/<repo>/<version>/<token>
 * - <version> like 4.0.0 or 4.0.0.0
 *
 * Example: packages/Magestore/Giftwrap-Magento1/4.0.0/d0e39be13c309177ebf3363a416f7615483271e6
 *
 * Class PackagesFile
 * @package controllers\Package
 */

class PackagesFile extends \Controller\ControllerAbstract {
    public function execute($app)
    {
        $params = $app->params;

        if(isset($params['owner']) && isset($params['repo']) && isset($params['version']) && isset($params['token'])){
            $token = new \Model\Repo\Token();
            if($params['token']){
                $token->load($app->params['token'], 'token');
            }
            if($token->getId()){
                $version = $params['version'];
                if(count(explode('.', $version)) == 3){
                    $version .= '.0';
                }
                $file = BP.DS.'repo'.DS.$params['owner'].DS.$params['repo'].DS.$version.DS.'zipball.zip';

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='
                    .strtolower($params['owner'].'_'.$params['repo'].'.zip'));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);

                return;
            }
        }

        $app->response()->setStatus(403);
    }
}

