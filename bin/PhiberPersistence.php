<?php
namespace bin;

use util\Execution;
use util\FuncoesReflections;
use util\FuncoesString;
use util\JsonReader;
use util\Internationalization;

/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:33
 */
class PhiberPersistence implements IPhiberPersistence
{

    private function prepare($sql)
    {
        Execution::start();
        return Link::getConnection()->prepare($sql);
    }

    private function bind($sql, $camposNome, $camposValores)
    {
        for ($i = 0; $i < count($camposNome); $i++) {
            $this->prepare($sql)->bindValue($camposNome[$i], $camposValores[$i]);
        }
    }

//TODO: FAZER OS METODOS DE CREATE QUERY PEGAR COMO PARAMETRO AS REFLECTIONS;
    private function execute($sql, $tabela)
    {

        try {
            if ($this->prepare($sql)->execute()) {
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
            $this->bind($sql, $campos, $camposV);
            return $this->execute($sql, $tabela);
        }else{
            return $sql;
        }
    }

    /**
     * @param $obj
     * @param array $conditions
     * @param array $conjunctions
     * @return mixed
     */
    public function update($obj, $conditions = [], $conjunctions = [])
    {
        return PhiberQueryWriter::update($obj, $conditions, $conjunctions);
    }

    public function delete($obj, $condicoes = [], $conjuncoes = [])
    {
        return PhiberQueryWriter::delete($obj, $condicoes, $conjuncoes);
    }

    public function rowCount($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement rowCount() method.
    }

    public function search($obj, $condicoes = null, $retornaPrimeiroValor = false)
    {
        // TODO: Implement searchWithConditions() method.
    }

    public function createQuery($query)
    {
        // TODO: Implement createQuery() method.
    }

}