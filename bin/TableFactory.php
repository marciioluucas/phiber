<?php
namespace bin;
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
    public function getConnection() {
        return Link::getConnection();
    }

    /**
     * @param $obj
     * @return mixed
     */
    abstract static function create($obj);

    /**
     * @return mixed
     */
    abstract static function alter($obj);

    /**
     * @return mixed
     */
    abstract static function drop($obj);


    abstract static function sync($obj);
}