<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Database;

class Collection {

    protected $table_name;
    protected $collection;
    protected $conn;
    protected $where;
    protected $columns;
    protected $limit = '30';

    public function __construct(\Model\Database\Pdo $conn){
        $this->conn = $conn;
    }

    /*public function select(){
        return $this->conn->select();
    }*/

    public function table($table){
        $this->table_name = $table;
        return $this;
    }

    public function where($where){
        $this->where = $where;
        return $this;
    }

    public function columns($columns){
        $this->columns = $columns;
        return $this;
    }

    public function limit($limit){
        $this->limit = $limit;
        return $this;
    }

    public function getSize(){
        if($this->table_name){
            $where = '';
            if($this->where){
                $where = ' WHERE '.$this->conn->getWhere($this->where);
            }
            $count = $this->conn->selectSql("
            SELECT count(*) AS c FROM {$this->table_name} {$where}
            ");
            if(isset($count['c'])){
                return (int) $count['c'];
            }
        }
        return 0;
    }

    public function getCount(){
        if($this->collection){
            return count($this->collection);
        }
        return 0;
    }

    public function load(){
        $this->collection = $this->conn->select($this->table_name, $this->where, $this->columns, $this->limit);
        return $this->collection;
    }

}