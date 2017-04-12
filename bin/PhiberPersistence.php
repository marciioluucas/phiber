<?php
namespace phiber\bin;
include_once '../vendor/autoload.php';
use \phiber\util\Execution;
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:33
 */
class PhiberPersistence extends PhiberPersistenceFactory
{
    public  function execute($sql)
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

    public  function create($obj)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $obj
     * @param array $conditions
     * @param array $conjunctions
     * @return mixed
     */
    public  function update($obj, $conditions = [], $conjunctions = [])
    {
        return \bin\PhiberQueryBuilder::update($obj, $conditions, $conjunctions);
    }

    public  function delete($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement delete() method.
    }

    public  function rowCount($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement rowCount() method.
    }

    public  function search($obj, $condicoes = null, $retornaPrimeiroValor = false)
    {
        // TODO: Implement searchWithConditions() method.
    }

    public  function createQuery($query)
    {
        // TODO: Implement createQuery() method.
    }

}