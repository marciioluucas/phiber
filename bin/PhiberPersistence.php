<?php
namespace bin;

use util\FuncoesReflections;
use util\FuncoesString;
use util\JsonReader;

/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:33
 */
class PhiberPersistence implements IPhiberPersistence
{

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

//TODO: FAZER OS METODOS DE CREATE QUERY PEGAR COMO PARAMETRO AS REFLECTIONS;

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
    function select($obj, $infos)
    {
        TableMysql::sync($obj);
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);

        $sql = PhiberQueryWriter::select([
            "table" => $tabela,
            "fields" => $infos['fields'],
            "conditions" => $infos['conditions'],
            "conjunctions" => $infos['conjunctions']
        ]);

        return $sql;
    }

    public
    function createQuery($query)
    {
        // TODO: Implement createQuery() method.
    }


}