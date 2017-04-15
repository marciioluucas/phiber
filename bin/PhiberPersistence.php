<?php
namespace bin;

use util\Execution;
use util\FuncoesReflections;
use util\FuncoesString;
use util\Internationalization;
use util\JsonReader;

/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:33
 */
class PhiberPersistence implements IPhiberPersistence
{
    private $connection;

    function __construct()
    {
        $this->connection = Link::getConnection();
    }


    private function bind($sql, $campoNome, $campoValor)
    {
        $this->connection->prepare($sql)->bindValue($campoNome, $campoValor);

    }

//TODO: FAZER OS METODOS DE CREATE QUERY PEGAR COMO PARAMETRO AS REFLECTIONS;
    private function execute($sql, $tabela)
    {

        try {
            if ($this->connection->prepare($sql)->execute()) {
                PhiberLogger::create("execution_query_success", "info", $tabela, Execution::end());
                return true;
            }

        } catch (PhiberException $pe) {
            PhiberLogger::create("execution_query_failure", "error", $tabela, Execution::end());
            throw new PhiberException(Internationalization::translate("execution_query_failure"));
        }
        return false;
    }

    public function create($obj)
    {
        TableMysql::sync($obj);
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);

        $sql = PhiberQueryWriter::create($tabela, $campos, $camposV);
        if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
            $pdo = Link::getConnection()->prepare($sql);
            for ($i = 1; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $pdo->bindValue($campos[$i], $camposV[$i]);
                }
            }
            if ($pdo->execute()) {
                return true;
            }
        }
        return $sql;

    }

    /**
     * @param $obj
     * @param array $conditions
     * @param array $conjunctions
     * @return mixed
     */
    public function update($obj, $conditions = [], $conjunctions = [])
    {
        TableMysql::sync($obj);
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);

        $sql = PhiberQueryWriter::update($tabela, $campos, $camposV, $conditions, $conjunctions);
        if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
            $pdo = Link::getConnection()->prepare($sql);
            for ($i = 1; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $pdo->bindValue($campos[$i], $camposV[$i]);
                }

            }

            while (current($conditions)) {
                $pdo->bindValue("condition_" . key($conditions), $conditions[key($conditions)]);
                next($conditions);
            }

            if ($pdo->execute()) {
                return true;
            }
        }
        return $sql;
    }

    public
    function delete($obj, $condicoes = [], $conjuncoes = [])
    {
        return PhiberQueryWriter::delete($obj, $condicoes, $conjuncoes);
    }

    public
    function rowCount($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement rowCount() method.
    }

    public
    function search($obj, $condicoes = null, $retornaPrimeiroValor = false)
    {
        // TODO: Implement searchWithConditions() method.
    }

    public
    function createQuery($query)
    {
        // TODO: Implement createQuery() method.
    }

}