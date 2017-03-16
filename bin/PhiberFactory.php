<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 16:31
 */
abstract class PhiberFactory
{
    public abstract function create($obj);
    public abstract function porId($obj);
    public abstract function update($obj, $id);
    public abstract function delete($obj, $id);
    public abstract function quantidadeRegistros($obj, $condicoes = []);
    public abstract function buscaPorCondicoes($obj, $condicoes, $retornaPrimeiroValor = false);
    public abstract function innerJoin($obj1, $obj2, $condicoes = null, $retornaSoPrimeiro = false, $campos = null);
}