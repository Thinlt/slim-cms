<?php

namespace Api\OAuth;


class Pdo implements \OAuth2\Storage\AccessTokenInterface {

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
        ), $config);
        if($debug){
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        $this->initial();
    }

    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        return $this->setUser($user_id, $oauth_token, $client_id);
    }

    public function getAccessToken($oauth_token)
    {
        return $this->getUserByToken($oauth_token);
    }

    public function addUser($user_id, $access_token, $user_name = '', $pass = ''){
        if($this->getUser($user_id)){
            $res = $this->update('user_id',
                array('user_id', 'access_token', 'user_name', 'pass'),
                array($user_id, $access_token, $user_name, $pass),
                $this->config['user_table']
            );
        }else{
            $res = $this->insert(
                array('user_id', 'access_token', 'user_name', 'pass'),
                array($user_id, $access_token, $user_name, $pass),
                $this->config['user_table']
            );
        }
        return $res;
    }

    public function addClient($client_id, $client_secret, $redirect_uri = '', $grant_types = '', $scope = '', $user_id = ''){
        if($this->getClient($client_id)){
            $res = $this->update('client_id',
                array('client_id', 'client_secret', 'redirect_uri', 'grant_types', 'scope', 'user_id'),
                array($client_id, $client_secret, $redirect_uri, $grant_types, $scope, $user_id),
                $this->config['client_table']
            );
        }else{
            $res = $this->insert(
                array('client_id', 'client_secret', 'redirect_uri', 'grant_types', 'scope', 'user_id'),
                array($client_id, $client_secret, $redirect_uri, $grant_types, $scope, $user_id),
                $this->config['client_table']
            );
        }
        return $res;
    }

    public function getUser($user_id){
        $data = $this->selectSql('SELECT * FROM '.$this->config['user_table'].' WHERE user_id = "'.$user_id.'"');
        if(!empty($data)){
            return $data;
        }
        return false;
    }

    public function getUserByToken($access_token){
        $data = $this->selectSql('SELECT * FROM '.$this->config['user_table'].' WHERE access_token = "'.$access_token.'"');
        if(!empty($data)){
            return $data;
        }
        return false;
    }

    public function getClient($client_id){
        $data = $this->selectSql('SELECT * FROM '.$this->config['client_table'].' WHERE client_id = "'.$client_id.'"');
        if(!empty($data)){
            return $data;
        }
        return false;
    }

    public function setUser($user_id, $access_token, $user_name = '', $pass = ''){
        return $this->addUser($user_id, $access_token, $user_name, $pass);
    }

    public function setClient($client_id, $client_secret, $redirect_uri = '', $grant_types = '', $scope = '', $user_id = ''){
        return $this->addClient($client_id, $client_secret, $redirect_uri, $grant_types, $scope, $user_id);
    }

    protected function insert($columns, $values, $table){
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
    protected function update($key, $columns, $values, $table){
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

    protected function delete($key, $value, $table){
        $sql = 'DELETE FROM '.$table;
        $sql .= ' WHERE '.$key.' = "'.$value.'"';
        return $this->db->exec($sql);
    }

    protected function selectSql($sql){
        $res = $this->db->query($sql);
        if($res instanceof \PDOStatement){
            $data = $res->fetchAll(\PDO::FETCH_ASSOC);
            if(count($data) > 1){
                return $data;
            }
            foreach($data as $d){
                return $d;
            }
        }
        return array();
    }

    public function getBuildSql()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS {$this->config['user_table']} (
          user_id            VARCHAR(80)   NOT NULL,
          access_token       VARCHAR(80)   NOT NULL,
          user_name          VARCHAR(2000),
          pass               VARCHAR(80),
          PRIMARY KEY (user_id)
        );

        CREATE TABLE IF NOT EXISTS {$this->config['client_table']} (
          client_id             VARCHAR(80)   NOT NULL,
          client_secret         VARCHAR(80)   NOT NULL,
          redirect_uri          VARCHAR(2000),
          grant_types           VARCHAR(80),
          scope                 VARCHAR(4000),
          user_id               VARCHAR(80),
          PRIMARY KEY (client_id)
        );
";
        return $sql;
    }

    protected function initial(){
        $can_init = false;
        try{
            $this->db->query("SELECT * FROM {$this->config['user_table']} LIMIT 1");
        }catch(\Exception $e){
            $can_init = true;
        }
        try{
            if($can_init){
                $this->db->exec($this->getBuildSql());
            }
        }catch(\Exception $e){
            throw new \Exception('Can not create init tables. Error with '.$e->getMessage());
        }
        return $this;
    }

    public function reInstallDb(){
        $this->db->exec('DROP TABLE '.$this->config['user_table']);
        $this->db->exec('DROP TABLE '.$this->config['client_table']);
        $this->initial();
        return $this;
    }

}

