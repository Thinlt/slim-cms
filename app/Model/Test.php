<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class Test extends \Model\Object {


    public function _construct()
    {
        $this->_init('test');
    }

    public function getVersion()
    {
        return '0.1.2';
    }

    protected function setup($install)
    {
        $install->run("
            DROP TABLE IF EXISTS {$this->getTable()};

            CREATE TABLE IF NOT EXISTS {$this->getTable()} (
              id     INTEGER PRIMARY KEY AUTOINCREMENT,
              test   VARCHAR(80) NOT NULL,
              test_2 INT(11),
              test_3 INT(11)
            );
        ");
    }
}