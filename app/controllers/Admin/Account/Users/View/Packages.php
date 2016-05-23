<?php

namespace controllers\Admin\Account\Users\View;


class Packages extends \Controller\Admin\ControllerAbstract {
    public function execute($app)
    {
        $this->loadView();
        $this->setTitle('View User Packages');

        /*$res = array(
            'packages' => array()
        );*/

        $user_id = $app->request()->params('id');
        /*$_POST = json_decode(file_get_contents('php://input'), true);
        $user_id = (isset($_POST['id'])) ? $_POST['id'] : '';*/
        if($user_id){
            $user = new \Model\User();
            $user->load($user_id);

            $token = new \Model\Repo\Token();
            $token->load($user->getData('token'), 'token');
            $repo_ids = explode(',', trim($token->getData('repo_ids'),','));
        }else{
            $repo_ids = array();
        }

        $repo = new \Model\Repo();
        $data = $repo->getCollection()->load(); //get array data
        //var_dump($data);die;

        $view = new \View\Admin\Account\View\Packages();
        $view->setData(array('user_id'=>$user_id, 'repos'=>$data, 'selected'=>$repo_ids));
        $view->reference('content', 'account.user.view.packages');

        $this->renderView();

        /*$res['packages'] = $data;
        echo json_encode($res);*/
    }

    protected function _roleName(){
        return 'Account.Users.View.Packages';
    }
}

