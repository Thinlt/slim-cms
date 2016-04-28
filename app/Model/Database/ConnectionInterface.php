<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Database;

interface ConnectionInterface {
    public function getTableName();

    public function getId();

    public function save();

    public function getData($key = null);

    public function setData($data = array());

    public function update($data = array());

    public function remove();

    public function load($id, $column = null);

    public function getCollection();
}