<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model;

class Repo extends \Model\ObjectAbstract {


    public function _construct()
    {
        $this->_init('git_repo');
    }

    /**
     * @return array of \Model\Repo\Release
     */
    public function getReleases(){
        $release = new \Model\Repo\Release();
        return $release->loadByRepoId($this->getId());
    }


    public function addRelease(\Model\Repo\Release $release){
        $release->setData('repo_id', $this->getId());
        $release->save();
        return $this;
    }

    protected function _beforeRemove()
    {
        $releases = $this->getReleases();
        foreach($releases as $obj){
            $obj->remove();
        }
    }

    public function getVersion()
    {
        return '0.1.0';
    }

    public function setup($install)
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