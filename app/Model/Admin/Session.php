<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Admin;

class Session extends \Model\Varien\Session {

    static $instance;

    public function __construct()
    {
        $this->init('admin');
        $this->createInstance();
    }


    protected function createInstance(){
        if(!static::$instance){
            static::$instance = $this;
        }
    }

    public function getInstance(){
        if(static::$instance){
            return static::$instance;
        }
        return new self;
    }

    public static function getSingleton(){
        if(static::$instance){
            return static::$instance;
        }
        return new self;
    }

    public function getError($cleaned = true){
        $error = $this->getData('error');
        if($cleaned){
            $this->unsetData('error');
        }
        return $error;
    }

    public function addError($message){
        $errors = $this->getData('error');
        if(is_array($errors)){
            array_push($errors, $message);
        }else{
            $errors = $message;
        }
        $this->setData('error', $errors);
        return $this;
    }

    public function isLoggedIn(){
        if($this->getUser()){
            $user = $this->getUser();
            if($user->getId() && $user->checkLogin($user->getData('user_name'), $user->getLoginPass())){
                return true;
            }
        }
        return false;
    }

    public function login($user_name, $password){
        $user = new \Model\Admin\User();
        if($user->checkLogin($user_name, $password)){
            $this->setUser($user);
            return true;
        }
        return false;
    }

    public function logout(){
        $this->clear();
        return $this;
    }

}