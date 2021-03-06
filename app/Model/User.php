<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class User extends \Model\ObjectAbstract {

    protected $role;

    public function _construct()
    {
        $this->_init('user', 'id');
    }

    public function newUser($user_name, $password, $name = '', $role = ''){
        $helper = new \Model\Helper();
        if($role && is_object($role)){
            $role = $role->getId();
        }
        $password = $this->hashPassword($user_name, $password);
        $this->setData(array(
            'user_name' =>  $user_name,
            'password'  =>  $password,
            'name'      =>  $name,
            'role_id'   =>  $role,
            'token'     =>  $helper->generateToken()
        ));
        $this->save();
        return $this;
    }

    public function userExist($user_name){
        $user = new self;
        $user->load($user_name, 'user_name');
        if($user->getId()){
            return true;
        }
        return false;
    }

    public function checkLogin($user_name, $password){
        if($this->userExist($user_name)){
            $this->load($user_name, 'user_name');
            if($this->getData('password') === $this->hashPassword($user_name, $password)){
                if($this->getRole() && $this->getRole()->getId()){
                    return true;
                }
            }
        }
        return false;
    }

    protected function hashPassword($user_name, $password){
        return substr(hash('sha1', $user_name.' - '.$password), 0, 40);
    }

    public function getVersion()
    {
        return '0.1.0';
    }

    public function setup($install)
    {
        $install->run("
            DROP TABLE IF EXISTS {$this->getTable()};

            CREATE TABLE IF NOT EXISTS {$this->getTable()} (
              id           INTEGER PRIMARY KEY AUTOINCREMENT,
              user_name    VARCHAR(255) UNIQUE NOT NULL,
              password     VARCHAR(255),
              token        VARCHAR(255),
              name         VARCHAR(255),
              role_id      VARCHAR(255)
            );
        ");
    }
}