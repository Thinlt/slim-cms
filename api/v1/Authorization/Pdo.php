<?php

namespace Api\Authorization;


class Pdo {

    protected $db;
    protected $config;

    public function __construct($config = array(), $debug = false)
    {
        $connection = new \PDO('sqlite:'.BP.DS.'api'.DS.'storage'.DS.'oauth.sqlite');
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
        $this->initial();
    }

    public function setConnection(\PDO $conn){
        $this->db = $conn;
        return $this;
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

    public function addClient($client_secret, $redirect_uri = '', $grant_types = '', $scope = '', $user_id = ''){
        if($this->getClientByToken($client_secret)){
            $res = $this->update('client_secret',
                array('client_secret', 'redirect_uri', 'grant_types', 'scope', 'user_id'),
                array($client_secret, $redirect_uri, $grant_types, $scope, $user_id),
                $this->config['client_table']
            );
        }else{
            $res = $this->insert(
                array('client_secret', 'redirect_uri', 'grant_types', 'scope'),
                array($client_secret, $redirect_uri, $grant_types, $scope),
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

    public function getUsers(){
        $data = $this->selectSql('SELECT * FROM '.$this->config['user_table'].' WHERE 1');
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

    public function getClientByToken($access_token){
        $data = $this->selectSql('SELECT * FROM '.$this->config['client_table'].' WHERE client_secret = "'.$access_token.'"');
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

    public function setScope($token, $scope){
        $scopeData = $this->getScope($token);
        if(!empty($scopeData)){
            return $this->insert(array('access_token', 'scope'), array($token, $scope), $this->config['scope_table']);
        }else{
            return $this->update('access_token', array('access_token', 'scope'), array($token, $scope), $this->config['scope_table']);
        }
    }

    public function getScope($token){
        return $this->selectSql('SELECT * FROM '.$this->config['scope_table'].' WHERE access_token = "'.$token.'"', true);
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

    protected function selectSql($sql, $fetch_one = false){
        $res = $this->db->query($sql);
        if($res instanceof \PDOStatement){
            if($fetch_one){
                $data = $res->fetch(\PDO::FETCH_ASSOC);
            }else{
                $data = $res->fetchAll(\PDO::FETCH_ASSOC);
            }
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
          client_id          INTEGER PRIMARY KEY AUTOINCREMENT,
          client_secret      VARCHAR(80)   NOT NULL,
          redirect_uri       VARCHAR(2000),
          grant_types        VARCHAR(80),
          scope              VARCHAR(4000)
        );

        CREATE TABLE IF NOT EXISTS {$this->config['scope_table']} (
          access_token          VARCHAR(80)   NOT NULL,
          scope                 VARCHAR(80)   NOT NULL,
          PRIMARY KEY (access_token)
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
    public function generateAccessToken()
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

