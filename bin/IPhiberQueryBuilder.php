<?php
namespace bin;
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:31
 */
interface IPhiberQueryBuilder
{
    public static function create($table, $fields, $fieldsValues);

    public static function update($table, $fields, $fieldsValues, $conditions = [], $conjunctions = []);

    public static function delete($obj, $condicoes = [], $conjuncoes = []);

    public static function rowCount($obj, $condicoes = [], $conjuncoes = []);

    public static function searchWithConditions($obj, $condicoes, $retornaPrimeiroValor = false);

    public static function createQuery($query);
}