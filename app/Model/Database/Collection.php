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

    /**
     * param where is string or array,
     * when use string must like "colName = 'value'"
     * when use array then must like array('colName' => 'value', 'colName2' => 'value2') for AND condition
     * or mixed condition array like array(array('colName' => 'value', 'colName2' => 'value2'), array('condition 2'));
     * for OR between array 1 and array 2 Ext: array(array1, array2) => array1 OR array2
     * @param $where
     * @return Collection
     */
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

    /**
     * get query string
     * @return string
     */
    public function getSql(){
        if($this->conn && !$this->conn->sql){
            $this->conn->buildSelect($this->table_name, $this->where, $this->columns, $this->limit);
        }

        if($this->conn && $this->conn->sql){
            return $this->conn->sql;
        }

        return 'No sql string';
    }

}