<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 18:52
 */
abstract class TableFactory
{
    public function getConnection(){
        return Link::getConnection();
    }

    abstract function getTable();
    abstract static function createTable($obj);
    abstract function alterTable();
    abstract function dropTable();

}