<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Database;

abstract class ObjectAbstract implements ConnectionInterface {

    protected $table_name;
    protected $pk_name;
    protected $id;
    protected $collection;
    protected $db;
    protected $conn;
    protected $data = array();
    protected $version = '0.1.0';

    public function __construct(){
        $this->db = new Db();
        $this->conn = $this->db->conn();

        $this->_construct(); //run init before run setup (mus have table name)

        if($this->versionCompare($this->db->getVersion($this->getTableName()))){
            $this->runSetup($this->db);
        }
    }

    public function _construct(){
        $this->_init(null, 'id');
    }

    protected function _init($table_name, $key = 'id'){
        $this->table_name = $table_name;
        $this->pk_name = $key;
    }

    public function getId(){
        return $this->id;
    }

    public function getTable(){
        return $this->getTableName();
    }

    public function getTableName(){
        return $this->table_name;
    }

    public function save(){
        if($this->getTableName() && $this->pk_name){
            $check = $this->conn->select($this->getTableName(), $this->pk_name.' = "'.$this->getId().'"',
                $this->pk_name, 1, true);
            if(!empty($check)){
                $this->data[$this->pk_name] = $this->getId();
                $this->conn->update($this->pk_name, array_keys($this->data), array_values($this->data), $this->getTableName());
            }else{
                $this->conn->insert(array_keys($this->data), array_values($this->data), $this->getTableName());
            }
        }
        return $this;
    }

    public function remove(){
        if($this->getTableName() && $this->pk_name){
            return $this->conn->delete($this->pk_name, $this->getId(), $this->getTableName());
        }
        return false;
    }

    public function setData($data = array())
    {
        $this->data = $data;
        return $this;
    }

    public function update($data = array()){
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function getData($name = null){
        if($name){
            if(isset($this->data[$name])){
                return $this->data[$name];
            }else{
                return null;
            }
        }
        return $this->data;
    }

    public function load($id, $column = null){
        if($this->getTableName()){
            if($column){
                $this->data = $this->conn
                    ->select($this->getTableName(), $column.' = "'.$id.'"', '*', '1', true);
            }else{
                $this->data = $this->conn
                    ->select($this->getTableName(), $this->pk_name.' = "'.$id.'"', '*', '1', true);
            }
        }
        return $this;
    }

    public function getCollection(){
        if($this->collection){
            return $this->collection;
        }
        $this->collection = new Collection($this->conn);
        $this->collection->table($this->getTableName())
            ->where(null)->columns('*');
        return $this->collection;
    }

    protected function setup($install){
        $install->run('');
        return $this;
    }

    protected function runSetup($install){
        $this->setup($install);
        $this->db->setVersion($this->getTable(), $this->getVersion());
    }

    public function versionCompare($other_version){
        return version_compare($this->getVersion(), $other_version);
    }

    public function getVersion(){
        return $this->version;
    }

    public function getVersionInstalled(){
        return $this->db->getVersion($this->getTable());
    }
}