<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class Repo extends \Model\Object {


    public function _construct()
    {
        $this->_init('git_repo');
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
              id        INTEGER PRIMARY KEY AUTOINCREMENT,
              repo_url  VARCHAR(255) NOT NULL,
              owner     VARCHAR(255),
              repo      VARCHAR(255)
            );
        ");
    }
}