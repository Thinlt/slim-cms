<?php

namespace controllers\Admin\Account\Users\Package;


class Save extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            'message' => ''
        );

        $_POST = json_decode(file_get_contents('php://input'), true);
        $packages = (isset($_POST['packages'])) ? $_POST['packages'] : '';
        $user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : '';

        $user = new \Model\User();
        $user->load($user_id);

        if($user->getId() && is_array($packages)){
            try{

                $token = new \Model\Repo\Token();
                $token->load($user->getData('token'), 'token');

                $repo_ids = array();
                foreach($packages as $key => $pk){
                    if($pk == true){
                        $repo_ids[] = str_replace('pk_', '', $key);
                    }
                }

                $repo_ids = implode(',', $repo_ids);
                if(!$token->getId()){
                    $token->setData('token', $user->getData('token'));
                    $token->setData('user_id', $user->getId());
                }
                $token->setData('repo_ids', $repo_ids);

                $token->save();

                $res['message'] = 'Save packages success!';
                $res['success'] = true;
            }catch(\Exception $e){
                $user->remove();
                $res['message'] = 'Save packages failed!';
                $res['success'] = false;
                $res['error'] = $e->getMessage();
            };

        }else{
            $res['message'] = 'User not found!';
        }

        echo json_encode($res);
    }

    protected function _roleName(){
        return 'Account.Users.Packages.Save';
    }
}

