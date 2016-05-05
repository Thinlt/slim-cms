<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Database;

class Connection implements ConnectionInterface {

    protected $table_name;
    protected $pk_name;
    protected $id;
    protected $collection;
    protected $db;
    protected $conn;
    protected $data = array();

    protected $model; //Object instance

    public function __construct(){
        $this->db = new Db();
        $this->conn = $this->db->conn();
    }

    public function init(){
        if($this->versionCompare($this->getVersionInstalled())){
            $this->runSetup($this->db);
        }
    }

    public function setModel($model){
        $this->model = $model;
        return $this;
    }

    /**
     * get table name from model
     * @return mixed
     */
    public function getTable(){
        return $this->getTableName();
    }

    /**
     * get table name from model
     * @return mixed
     */
    public function getTableName(){
        if($this->model){
            return $this->model->getTableName();
        }
        return false;
    }

    public function getPkName(){
        if($this->model){
            return $this->model->getPkName();
        }
        return false;
    }

    public function save(){
        if($this->getTableName() && $this->model){
            $check = $this->conn->select($this->getTableName(), $this->getPkName().' = "'.$this->model->getId().'"',
                $this->getPkName(), 1, true);
            if(!empty($check)){
                $this->data[$this->getPkName()] = $this->model->getId();
                $this->conn->update($this->getPkName(), array_keys($this->data), array_values($this->data), $this->getTableName());
            }else{
                $this->conn->insert(array_keys($this->data), array_values($this->data), $this->getTableName());
            }
        }
        return $this;
    }

    public function remove(){
        if($this->getTableName() && $this->getPkName() && $this->model){
            return $this->conn->delete($this->getPkName(), $this->model->getId(), $this->getTableName());
        }
        return false;
    }

    public function setData($data = array(), $value = null)
    {
        if(is_array($data)){
            $this->data = $data;
        }else{
            $this->data[$data] = $value;
        }
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
        if($this->getTableName() && $this->model){
            if($column){
                $this->data = $this->conn
                    ->select($this->getTableName(), $column.' = "'.$id.'"', '*', '1', true);
            }else{
                $this->data = $this->conn
                    ->select($this->getTableName(), $this->model->getPkName().' = "'.$id.'"', '*', '1', true);
            }
            if(isset($this->data[$this->model->getPkName()])){
                $this->id = $this->data[$this->model->getPkName()];
            }
        }
        return $this->data;
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

    /**
     * @param $installer \Model\Database\Db
     */
    protected function runSetup($installer){
        if($this->model){
            try{
                if(is_callable(array($this->model, 'setup'))){
                    call_user_func(array($this->model, 'setup'), $installer);
                    //$this->model->setup($installer);
                    $this->db->setVersion($this->getTable(), $this->model->getVersion());
                }
            }catch(\Exception $e){
                throw $e;
            }
        }
    }

    public function versionCompare($other_version){
        if($this->model){
            return version_compare($this->model->getVersion(), $other_version);
        }
        return false;
    }

    protected function getVersionInstalled(){
        return $this->db->getVersion($this->getTableName());
    }
}