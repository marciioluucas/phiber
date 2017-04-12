<?php
namespace bin;
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:33
 */
class PhiberPersistence extends PhiberPersistenceFactory
{
    public static function execute($sql)
    {

        $pdo = self::getConnection()->prepare($sql);
        for ($i = 0; $i < count($camposNome); $i++) {
            $pdo->bindValue($camposNome[$i], $camposValores[$i]);
        }

        if ($pdo->execute()) {
            PhiberLogger::create("execution_query_success", "info", $tabela, Execution::end());
            return true;
        } else {
            PhiberLogger::create("execution_query_failure", "error", $tabela, Execution::end());
        }
        return false;
    }

    public static function create($obj)
    {
        // TODO: Implement create() method.
    }

    public static function update($obj, $id)
    {
        // TODO: Implement update() method.
    }

    public static function delete($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement delete() method.
    }

    public static function rowCount($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement rowCount() method.
    }

    public static function search($obj, $condicoes = null, $retornaPrimeiroValor = false)
    {
        // TODO: Implement searchWithConditions() method.
    }

    public static function createQuery($query)
    {
        // TODO: Implement createQuery() method.
    }

}