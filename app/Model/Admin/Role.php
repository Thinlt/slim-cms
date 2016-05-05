<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Admin;

class Role extends \Model\ObjectAbstract {


    protected $parent;

    public function _construct()
    {
        $this->_init('admin_role');
    }

    public function newRole($name, $options = '', $parent_id = null, $inherit = false){
        $this->setData(array('role_name'=>$name, 'parent_id'=>$parent_id, 'inherit'=>$inherit));
        $this->setOptions($options);
        $this->save();
        return $this;
    }

    /**
     * check role with give source name allowed
     * @param $source_name
     * @return bool
     */
    public function checkAllow($source_name){
        if($this->getId()){
            $options = $this->getOptions();
            if(isset($options['sources']) && is_array($options['sources'])
                && in_array($source_name, $options['sources'])){
                return true;
            }
            if(isset($options['sources']) && $options['sources'] == 'all'){
                return true;
            }
            if(isset($options['sources']) && $options['sources'] == 'Admin'){
                return true;
            }
        }
        return false;
    }

    public function setOptions($options){
        $serialized = serialize($options);
        //$this->setData('options', str_replace('"', "'", $serialized));
        $this->setData('options', $serialized);
        return $serialized;
    }

    public function getOptions(){
        if($this->getData('inherit') && $this->getParent()){
            $parent = $this->getParent();
            return $parent->getOptions();
        }
        return unserialize($this->getData('options'));
    }

    public function getParent(){
        if($this->getData('parent_id')){
            if($this->parent){
                return $this->parent;
            }else{
                $this->parent = new self;
                $this->parent->load($this->getData('parent_id'));
                return $this->parent;
            }
        }
        return false;
    }

    /**
     * get from variable protected $version = '0.1.0' when not redefine this method
     * @return string
     */
    public function getVersion()
    {
        return '0.1.0';
    }

    /**
     * @param $install \Model\Database\Db
     * @return $this
     */
    public function setup($install)
    {
        $install->run("
            DROP TABLE IF EXISTS {$this->getTable()};

            CREATE TABLE IF NOT EXISTS {$this->getTable()} (
              id          INTEGER PRIMARY KEY AUTOINCREMENT,
              parent_id   INTEGER NULL,
              role_name   VARCHAR(255) NOT NULL,
              options     TEXT NULL,
              inherit     BOOLEAN
            );
        ");
    }
}