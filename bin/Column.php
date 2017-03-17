<?php
require_once '../util/Annotations.php';
require_once 'IColumn.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 13:19
 */
class Column implements IColumn
{
    private $name;
    private $type;
    private $primaryKey; // boolean
    private $autoIncrement; // boolean
    private $notNull;

    /**
     * @return mixed
     */
    public function get($prop)
    {
        return $this->$prop;
    }

    /**
     * @param mixed $name
     */
    public function set($prop, $value)
    {
        $this->$prop = $value;
    }

    public function verificaSeTemNomeParaTabelaDiferente($obj) {
        $annotation = Annotations::getAnnotation($obj);
    }





}