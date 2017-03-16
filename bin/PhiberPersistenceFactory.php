<?php

require_once 'Link.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 16:31
 */
abstract class PhiberPersistenceFactory
{
    public function getConnection(){
        return Link::getConnection();
    }

    public abstract static function create($obj);
    public abstract static function porId($obj);
    public abstract static function update($obj, $id);
    public abstract static function delete($obj, $id);
    public abstract static function quantidadeRegistros($obj, $condicoes = []);
    public abstract static function buscaPorCondicoes($obj, $condicoes, $retornaPrimeiroValor = false);
//    public abstract static function innerJoin($obj1, $obj2, $condicoes = null, $retornaSoPrimeiro = false, $campos = null);
}