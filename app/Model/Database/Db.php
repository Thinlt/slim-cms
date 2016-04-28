<?php

namespace Model\Database;


class Db {

    protected $conn;

    public $db_name;

    public function __construct($debug = false)
    {
        if(!$debug){
            $debug = \App::getInstance()->config('debug');
        }
        $this->db_name = \App::getInstance()->config('db_name');
        if(!$this->db_name){
            $this->db_name = 'slim_db';
        }
        $conn = new \PDO('sqlite:'.BP.DS.'etc'.DS.'db'.DS.$this->db_name.'.sqlite');
        $this->conn = new Pdo($conn, array(), $debug);

        $this->initial();
    }

    public function setDb(\Model\Database\Pdo $db){
        $this->conn = $db;
        return $this;
    }


    public function conn(){
        return $this->conn;
    }

    public function name(){
        return $this->db_name;
    }

    public function run($sql){
        return $this->conn()->db()->exec($sql);
    }

    public function getVersion($table_name){
        $res = $this->conn()->selectSql(
            'SELECT * FROM model_config_setup WHERE table_name = "'.$table_name.'"', true);
        if(!empty($res) && isset($res['version'])){
            return $res['version'];
        }
        return false;
    }

    public function setVersion($table, $version){
        if(!$this->getVersion($table)){
            $res = $this->conn()->insert(array('table_name', 'version'), array($table, $version), 'model_config_setup');
        }else{
            $res = $this->conn()->update('table_name', array('table_name', 'version'), array($table, $version), 'model_config_setup');
        }
        return $res;
    }

    public function getBuildSql()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS model_config_setup (
          id          INTEGER PRIMARY KEY AUTOINCREMENT,
          table_name  VARCHAR(80) NOT NULL,
          version     VARCHAR(10)
        );
";
        return $sql;
    }

    protected function initial(){
        $can_init = false;
        try{
            $this->conn()->db()->query("SELECT * FROM model_config_setup LIMIT 1");
        }catch(\Exception $e){
            $can_init = true;
        }
        try{
            if($can_init){
                $this->conn()->db()->exec($this->getBuildSql());
            }
        }catch(\Exception $e){
            throw new \Exception('Can not create init tables. Error with '.$e->getMessage());
        }
        return $this;
    }

    public function reInstallDb(){
        $this->conn()->db()->exec('DROP TABLE model_config_setup');
        $this->initial();
        return $this;
    }



}

