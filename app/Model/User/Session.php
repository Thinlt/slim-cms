<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\User;

class Session extends \Model\Varien\Session {

    static $instance;

    public function __construct()
    {
        $this->init('user');
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
        $session = new self;
        if($session->getData('frontend')){
            $session = $session->getData('frontend');
        }
        return $session;
    }

    public function getError($cleaned = true){
        $error = $this->getData('error');
        if($cleaned){
            $this->unsetData('error');
        }
        return $error;
    }

    public function isLoggedIn(){
        if($this->getUser()){
            $user = $this->getUser();
            if($user->getId() && $user->checkLogin($user->getData('user_name'), $user->getData('password'))){
                return true;
            }
        }
        return false;
    }

    public function login($user_name, $password){
        if(!$this->isLoggedIn()){
            $user = new \Model\User();
            if($user->checkLogin($user_name, $password)){
                $this->setUser($user);
                return true;
            }
        }
        return false;
    }

    public function logout(){
        $this->clear();
        return $this;
    }

}