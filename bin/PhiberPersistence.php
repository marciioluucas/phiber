<?php
namespace bin;

use PDO;
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
    private static $infos = [];
    private static $infosMergeds = [];


    public function create($obj)
    {
        TableMysql::sync($obj);
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);

        $sql = PhiberQueryWriter::create([
            "table" => $tabela,
            "fields" => $campos,
            "values" => $camposV


        ]);
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
    public function update($obj, $info = null)
    {
        TableMysql::sync($obj);
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);
        $conditions = self::$infosMergeds['fields_and_values'];

        $sql = PhiberQueryWriter::update([
            "table" => $tabela,
            "fields" => $campos,
            "values" => $camposV,
            "where" => self::$infosMergeds['where'],

        ]);
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
    function delete($obj, $infos = null)
    {
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        if ($infos != null) {
            $sql = PhiberQueryWriter::select([
                "table" => $tabela,
                "conditions" => isset($infos['conditions']) ? $infos['conditions'] : null,
                "conjunctions" => isset($infos['conjunctions']) ? $infos['conjunctions'] : null
            ]);
        } else {


            $sql = PhiberQueryWriter::delete([
                "table" => $tabela,
                "where" => self::$infosMergeds['where'],

            ]);
        }
        return $sql;
    }

    public function rowCount($obj, $condicoes = [], $conjuncoes = [])
    {
        // TODO: Implement rowCount() method.
    }

    public function select($obj, $infos = null)
    {
        TableMysql::sync($obj);
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        if ($infos != null) {
            $sql = PhiberQueryWriter::select([
                "table" => $tabela,
                "fields" => isset($infos['fields']) ? $infos['fields'] : "*",
                "conditions" => isset($infos['conditions']) ? $infos['conditions'] : null,
                "conjunctions" => isset($infos['conjunctions']) ? $infos['conjunctions'] : null
            ]);
        } else {
            $fields = isset(
                self::$infosMergeds['fields']) ?
                implode(", ", self::$infosMergeds['fields']) :
                "*";

            $sql = PhiberQueryWriter::select([
                "table" => $tabela,
                "fields" => $fields,
                "where" =>  isset(self::$infosMergeds['where']) ?
                            self::$infosMergeds['where'] :
                            null,

            ]);
        }

        if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
            $pdo = Link::getConnection()->prepare($sql);
            if ($infos != null) {
                for ($i = 0; $i < count($infos['conditions']); $i++) {
                    $pdo->bindValue(
                        "condition_" . $infos['conditions'][$i][0],
                        $infos['conditions'][$i][2]
                    );
                }
            } else {
                if (isset(self::$infosMergeds['fields_and_values'])) {
                    for ($i = 0; $i < count(self::$infosMergeds['fields_and_values']); $i++) {
                        $pdo->bindValue(
                            "condition_" . key(self::$infosMergeds['fields_and_values']),
                            self::$infosMergeds['fields_and_values'][key(self::$infosMergeds['fields_and_values'])]
                        );
                    }
                }
            }
            if ($pdo->execute()) {
                return $pdo->fetchAll((PDO::FETCH_ASSOC));
            }
        }
        return $sql;
    }

    public
    function createQuery($query)
    {
        // TODO: Implement createQuery() method.
    }


    public static function add($infos)
    {
        array_push(self::$infos, $infos);
        self::mergeSqlInformation();

    }

    public function show()
    {

        return self::$infosMergeds;
    }


    private function mergeSqlInformation()
    {
        array_push(self::$infos, Restrictions::getFieldsAndValues());
        for ($i = 0; $i < count(self::$infos) - 1; $i++) {
            self::$infosMergeds[array_keys(self::$infos[$i])[0]] =
                self::$infos[$i][array_keys(self::$infos[$i])[0]];
        }
    }

}