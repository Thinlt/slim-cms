<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class UserRepo extends \Model\Object {


    public function _construct()
    {
        $this->_init('user_repo');
    }

    public function getVersion()
    {
        return '0.1.0';
    }

    protected function setup($install)
    {
        $install->run("
            DROP TABLE IF EXISTS {$this->getTable()};

            CREATE TABLE IF NOT EXISTS {$this->getTable()} (
              id            INTEGER PRIMARY KEY AUTOINCREMENT,
              user_id       VARCHAR(80) NOT NULL,
              access_token  VARCHAR(80),
              repo_id       VARCHAR(255)
            );
        ");
    }
}