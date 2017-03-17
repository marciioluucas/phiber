<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 13:19
 */
class Column extends ColumnFactory
{
    private $name;
    private $type;
    private $primaryKey; // boolean
    private $autoIncrement; // boolean
    private $notNull; // boolean


    function getColumns($obj)
    {

    }
}