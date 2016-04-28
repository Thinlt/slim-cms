<?php

namespace Model\Database;


class Pdo {

    protected $db;
    protected $config;

    public function __construct(\PDO $connection, $config = array(), $debug = false)
    {
        if (!$connection instanceof \PDO) {
            if (is_string($connection)) {
                $connection = array('dsn' => $connection);
            }
            if (!is_array($connection)) {
                throw new \InvalidArgumentException('First argument to OAuth2\Storage\Pdo must be an instance of PDO, a DSN string, or a configuration array');
            }
            if (!isset($connection['dsn'])) {
                throw new \InvalidArgumentException('configuration array must contain "dsn"');
            }
            // merge optional parameters
            $connection = array_merge(array(
                'username' => null,
                'password' => null,
                'options' => array(),
            ), $connection);
            $connection = new \PDO($connection['dsn'], $connection['username'], $connection['password'], $connection['options']);
        }
        $this->db = $connection;

        $this->config = array_merge(array(
            'user_table' => 'oauth_user',
            'client_table' => 'oauth_client',
            'scope_table' => 'oauth_scope_token',
        ), $config);
        if($debug){
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    public function db(){
        return $this->db;
    }

    public function setConnection(\PDO $conn){
        $this->db = $conn;
        return $this;
    }

    public function insert($columns, $values, $table){
        $res = false;
        try{
            $sql = 'INSERT INTO '.$table.' (';
            $cols = '';
            foreach($columns as $col){
                $cols .= $col.', ';
            }
            $sql .= trim($cols, ', ').') VALUES ';
            $vals = '';
            if(is_array($values[0])){
                foreach($values as $val){
                    $vs = '(';
                    foreach($val as $v){
                        $vs .= '"'.$v.'", ';
                    }
                    $vals .= trim($vs, ', ') . '), ';
                }
                $vals = trim($vals, ', ');
            }else{
                $vals .= '(';
                foreach($values as $val){
                    $vals .= '"'.$val.'", ';
                }
                $vals = trim($vals, ', ') . ')';
            }

            $sql .= $vals;
            $res = $this->db->exec($sql);
            return $res;
        }catch(\Exception $e){
            return $res;
        }
    }

    /**
     * @param $key      value in $columns that is primary or where clause
     * @param $columns  columns name of table
     * @param $values   multiple values of fields
     * @param $table    table name
     * @return bool|int pdo result
     */
    public function update($key, $columns, $values, $table){
        $sql = 'UPDATE '.$table.' SET ';
        if(count($columns) != count($values)){
            return false;
        }
        for($i = 0; $i<count($columns); $i++){
            $sql .= $columns[$i] .' = "'.$values[$i].'", ';
        }
        $sql = trim($sql, ', ');
        $sql .= ' WHERE '.$key.' = "'. $values[array_search($key, $columns)].'"';
        return $this->db->exec($sql);
    }

    public function delete($key, $value, $table){
        $sql = 'DELETE FROM '.$table;
        $sql .= ' WHERE '.$key.' = "'.$value.'"';
        return $this->db->exec($sql);
    }

    public function select($table, $where, $columns = '*', $limit = '', $fetch_one = false){
        $col = '';
        if(!is_array($columns)){
            $columns = array($columns);
        }
        foreach($columns as $cl){
            $col .= $cl.', ';
        }
        $col = trim($col, ', ');

        $sql = 'SELECT '.$col.' FROM '.$table;

        $_where = $this->getWhere($where);
        if($_where){
            $sql .= ' WHERE '.$_where;
        }

        if($limit){
            $sql .= ' LIMIT '.$limit;
        }

        return $this->selectSql($sql, $fetch_one);
    }

    public function getWhere($where){
        $_where = '';
        if(is_array($where)){
            foreach($where as $col_name => $cond){
                if(is_array($cond)){
                    $_where .= '(';
                    foreach($cond as $col_name2 => $cond2){
                        $_where .= $col_name2.' = "'.$cond2.'" AND ';
                    }
                    $_where = trim($_where, 'AND ');
                    $_where .= ')';
                    $_where .= ' OR ';
                }else{
                    $_where .= $col_name.' = "'.$cond.'" AND ';
                }
            }
            $_where = trim($_where, 'AND ');
            $_where = trim($_where, 'OR ');
        }else{
            $_where = $where;
        }
        return $_where;
    }

    public function selectSql($sql, $fetch_one = false){
        $res = $this->db->query($sql);
        if($res instanceof \PDOStatement){
            if($fetch_one){
                return $res->fetch(\PDO::FETCH_ASSOC);
            }
            $data = $res->fetchAll(\PDO::FETCH_ASSOC);
            if(count($data) > 1){
                return $data;
            }
            if(is_array($data)){
                foreach($data as $d){
                    return $d;
                }
            }
        }
        return array();
    }

    /**
     * Generates an unique access token.
     *
     * Implementing classes may want to override this function to implement
     * other access token generation schemes.
     *
     * @return
     * An unique access token.
     *
     * @ingroup oauth2_section_4
     */
    public function generateToken()
    {
        if (function_exists('mcrypt_create_iv')) {
            $randomData = mcrypt_create_iv(20, MCRYPT_DEV_URANDOM);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $randomData = openssl_random_pseudo_bytes(20);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 20);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        // Last resort which you probably should just get rid of:
        $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);

        return substr(hash('sha512', $randomData), 0, 40);
    }

}

