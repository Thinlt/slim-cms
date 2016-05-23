<?php

namespace controllers\Admin\Account\Users;


class Add extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            'message' => ''
        );

        $_POST = json_decode(file_get_contents('php://input'), true);
        $user_name = (isset($_POST['user_name'])) ? $_POST['user_name'] : '';
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';

        $user = new \Model\User();
        if(!$user_name){
            $res = array(
                'message' => 'User name is null'
            );
        }else{
            $user->load($user_name, 'user_name');
            if(!$user->getId()){
                $user->newUser($user_name, '', $name);
                try{
                    $repoToken = new \Model\Repo\Token();
                    $repoToken->load($user->getId(), 'user_id');
                    $repoToken->setData('token', $user->getData('token'));
                    $repoToken->setData('user_id', $user->getId());
                    $repoToken->save();

                    $res['message'] = 'Add user success!';
                    $res['success'] = true;
                }catch(\Exception $e){
                    $user->remove();
                    $res['message'] = 'Add user failed!';
                    $res['success'] = false;
                    $res['error'] = $e->getMessage();
                };

            }else{
                $res['message'] = 'User name already existed!';
            }
        }

        echo json_encode($res);
    }

    protected function _roleName(){
        return 'Repositories.Add';
    }
}

