<?php

namespace Api\Composer;


class Json extends \Api\ApiAbstract{

    /**
     * Method: POST api/composer/json/user|token/<user_name>|<token>
     * Request data: packages = ['<package_name>', ...] or packages = [['name'=>'<package_name>', 'version'=>'<version>'], ...]
     * @param $params
     */
    public function execute($params){

        $token = (isset($params['token'])) ? $params['token'] : '';
        if(isset($params['user'])){
            $user = new \Model\User();
            $user->load($params['user'], 'user_name');
            $token = $user->getData('token');
        }

        if($token){
            //check is secure url
            $is_secure = false;
            if(strpos($this->app()->config('base_url_secure'), $this->app()->request->getUrl()) !== false
                && strpos($this->app()->config('base_url'), $this->app()->request->getUrl()) == false
            ){
                $is_secure = true;
            }

            $bodyParams = $this->app()->request()->params();

            $packages = (isset($bodyParams['packages'])) ? $bodyParams['packages'] : array();
            if(!is_array($packages)){
                $packages = [$packages];
            }

            /**
             * Get packages accept with param packages = ['<package_name>', ...]
             * or packages = [['name'=>'<package_name1>', 'version'=>'<version>'], ...]
             */
            $require = [];
            foreach ($packages as $package) {
                if(is_array($package)){
                    if(isset($package['name']) && isset($package['version'])){
                        $verSplit = explode('.', $package['version']);
                        if(count($verSplit) >= 4){
                            $package['version'] = $verSplit[0].'.'.$verSplit[1].'.'.$verSplit[2];
                        }
                        $require[$package['name']] = $package['version'];
                    }
                }else{
                    $require[$package] = '*';
                }
            }

            $json = array(
                'repositories' => [[
                    "type" => "composer",
                    "url" => $this->app()->getUrl("packages/".$token."/", ['_secure'=>$is_secure])
                ]],
                'require' => $require,
                'config' => ["secure-http" => $is_secure]
            );

        }else{

            $json = array(
                'message' => 'User not generated token',
                'error' => true
            );
        }

        $this->sendResponse($json, 202);
    }

}
