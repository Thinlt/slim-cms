<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class Release extends \Model\Object {


    public function _construct()
    {
        $this->_init('git_release');
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
              id           INTEGER PRIMARY KEY AUTOINCREMENT,
              repo_id      INTEGER NOT NULL,
              release_id   VARCHAR(255) NOT NULL,
              tag_name     VARCHAR(255) NOT NULL,
              name         VARCHAR(255) NOT NULL,
              draft        BOOLEAN,
              tarball_url  TEXT,
              zipball_url  TEXT,
              html_url     TEXT,
              url          TEXT,
              body         TEXT
            );
        ");
    }
}