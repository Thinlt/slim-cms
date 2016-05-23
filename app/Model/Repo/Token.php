<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Repo;

class Token extends \Model\ObjectAbstract {


    public function _construct()
    {
        $this->_init('git_repo_token');
    }



    public function getVersion()
    {
        return '0.1.1';
    }

    public function setup($install)
    {
        $install->run("
            DROP TABLE IF EXISTS {$this->getTable()};

            CREATE TABLE IF NOT EXISTS {$this->getTable()} (
              id           INTEGER PRIMARY KEY AUTOINCREMENT,
              token        VARCHAR(255) UNIQUE NOT NULL,
              user_id      VARCHAR(255),
              repo_ids     TEXT
            );
        ");
    }
}