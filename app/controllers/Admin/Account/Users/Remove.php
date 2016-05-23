<?php

namespace controllers\Admin\Account\Users;


class Remove extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $res = array(
            'message' => ''
        );

        $_POST = json_decode(file_get_contents('php://input'), true);
        $id = (isset($_POST['id'])) ? $_POST['id'] : '';

        $user = new \Model\User();
        if(!$id){
            $res = array(
                'message' => 'Id is null'
            );
        }else{
            $user->load($id);
            if($user->getId()){
                $token = $user->getData('token');
                $user->remove();

                try{
                    $repoToken = new \Model\Repo\Token();
                    $repoToken->load($token, 'token');
                    $repoToken->remove();

                    $res['message'] = 'Remove user success!';
                    $res['success'] = true;
                }catch(\Exception $e){
                    $user->remove();
                    $res['message'] = 'Remove user failed!';
                    $res['success'] = false;
                    $res['error'] = $e->getMessage();
                };

            }else{
                $res['message'] = 'User not found!';
            }
        }

        echo json_encode($res);
    }

    protected function _roleName(){
        return 'Account.Users.Remove';
    }
}

