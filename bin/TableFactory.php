<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 18:52
 */
abstract class TableFactory
{
    /**
     * @return mysqli|PDO
     */
    public function getConnection(){
        return Link::getConnection();
    }

    /**
     * @return mixed
     */
    abstract function getTable();

    /**
     * @param $obj
     * @return mixed
     */
    abstract static function createTable($obj);

    /**
     * @return mixed
     */
    abstract function alterTable();

    /**
     * @return mixed
     */
    abstract function dropTable();

}