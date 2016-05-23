<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class ObjectAbstract extends \Model\Varien\Object {

    protected $table_name;
    protected $pk_name;
    protected $collection;
    protected $version = '0.1.0';

    private $cache_key; //cache key for get connection

    protected function _construct()
    {
        $this->cache_key = substr(md5(rand()), 0, 10);
        $this->_init(null, 'id');
    }

    protected function _init($table_name, $key = 'id'){
        $this->table_name = $table_name;
        $this->pk_name = $key;
        $connection = $this->getConnection();
        $connection->init();
    }

    /**
     * get the PDO connector to write database
     * @return mixed
     */
    protected function getConnection(){
        $connection = \App::getInstance()->getConnection($this->cache_key);
        $connection->setModel($this);
        return $connection;
    }

    public function getId(){
        if($this->pk_name){
            return $this->getData($this->pk_name);
        }
        return null;
    }

    public function setId($id){
        $this->setData($this->pk_name, $id);
        return $this;
    }

    /**
     * TODO: get table name from model
     * @return mixed
     */
    public function getTable(){
        return $this->getTableName();
    }

    /**
     * TODO: get table name from model
     * @return mixed
     */
    public function getTableName(){
        return $this->table_name;
    }

    public function getPkName(){
        return $this->pk_name;
    }

    public function update($new_data = array()){
        $data = $this->getData();
        $this->setData(array_merge($data, $new_data));
        return $this;
    }

    public function load($id, $column = null){
        $data = $this->getConnection()->load($id, $column);
        if($data){
            $this->setData($data);
            if(isset($data[$this->pk_name])){
                $this->setId($data[$this->pk_name]);
            }
        }
        return $this;
    }

    public function save(){
        $this->_saveBefore();
        $this->getConnection()
            ->setData($this->getData())
            ->save();
        $this->_saveAfter();
        return $this;
    }

    protected function _saveBefore(){
        return $this;
    }

    protected function _saveAfter(){
        return $this;
    }

    public function remove(){
        if($this->getTableName() && $this->pk_name){
            $this->_beforeRemove();
            $this->getConnection()->remove();
        }
        return false;
    }

    protected function _beforeRemove(){
        return $this;
    }

    public function getCollection(){
        $this->collection = $this->getConnection()->getCollection();
        return $this->collection;
    }

    /**
     * get collection with model for each item
     * @return array
     */
    public function loadCollection(){
        if($this->collection){
            $collection = $this->collection->load();
            if(isset($collection[$this->getPkName()]) && $collection[$this->getPkName()] != null){
                $collection = array($collection);
            }
            $data = array();
            foreach ($collection as $item) {
                $model = new $this;
                $model->setData($item);
                $data[] = $model;
            }
            return $data;
        }
        return false;
    }

    public function getVersion(){
        return $this->version;
    }

    /**
     * @param $install \Model\Database\Db
     * @return $this
     */
    public function setup($installer){
        $installer->run('');
        return $this;
    }

    public function clean(){
        $this->setId('');
        $this->setData(array());
        return $this;
    }
}