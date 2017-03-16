<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 16:31
 */
interface IPhiber
{
    public function create($obj);
    public function porId($obj);
    public function update($obj, $id);
    public function delete($obj, $id);
    public function quantidadeRegistros($obj, $condicoes = []);
    public function buscaPorCondicoes($obj, $condicoes, $retornaPrimeiroValor = false);
    public function innerJoin($obj1, $obj2, $condicoes = null, $retornaSoPrimeiro = false, $campos = null);
}