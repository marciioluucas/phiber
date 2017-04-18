<?php
namespace bin;

use util\Execution;
use util\Internationalization;
use util\JsonReader;

/**
 * Created by PhpStorm
 * User: Lukee
 * Date: 20/10/2016
 * Time: 22:14
 */
class PhiberQueryWriter implements IPhiberQueryBuilder
{

    /**
     * @param $object
     * @return mixed
     * @throws \Exception
     * Faz a criação de um registro no banco com os dados de um objeto.
     * Make an insert of an object in the database
     */
    public static function create($infos)
    {
        $tabela = $infos['table'];
        $campos = $infos['fields'];
        $camposV = $infos['values'];
        try {
            $camposNome = [];


            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $camposNome[$i] = $campos[$i];
                }
            }


            $camposNome = array_values($camposNome);
            $sqlInsert = "INSERT INTO $tabela (";
            for ($i = 0; $i < count($camposNome); $i++) {
                if ($i != count($camposNome) - 1) {
                    $sqlInsert .= $camposNome[$i] . ", ";
                } else {
                    $sqlInsert .= $camposNome[$i] . ") VALUES (";
                }
            }

            for ($j = 0; $j < count($camposNome); $j++) {
                if ($j != count($camposNome) - 1) {
                    $sqlInsert .= ":" . $camposNome[$j] . ", ";
                } else {
                    $sqlInsert .= ":" . $camposNome[$j] . ")";
                }
            }

            return $sqlInsert;

        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
    }

    /**
     * @param $object
     * @param $id
     * @return mixed
     * @throws PhiberException
     */
    public static function update($infos)
    {

        $tabela = $infos['table'];
        $conditions = isset($infos['conditions']) ? $infos['conditions'] : null;
        $conjunctions = isset($infos['conjunctions']) ? $infos['conjunctions'] : null;
        $campos = $infos['fields'];
        $camposV = $infos['values'];
        $whereCriteria = $infos['where'];

        try {
            $camposNome = [];
            $camposValores = [];
            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null || $camposV[$i] != "") {
                    $camposNome[$i] = $campos[$i];
                }
            }
            for ($i = 0; $i < count($camposV); $i++) {
                if ($camposV[$i] != null || $camposV[$i] != "") {
                    $camposValores[$i] = $camposV[$i];
                }
            }

            $nomeCampos = [];
            $camposNome = array_values($camposNome);
            $sqlUpdate = "UPDATE $tabela SET ";

            for ($i = 0; $i < count($camposNome); $i++) {
                if ($i != count($camposNome) - 1) {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i] . ", ";
                } else {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i];
                }



            }
            if ($conditions != null && $whereCriteria == null) {
                $conditionsComIndexInt = array_keys($conditions);
                for ($i = 0; $i < count($conditions); $i++) {
                    $nomeCampos[$i] = $conditionsComIndexInt[$i];
                }
                $valoresCampos = [];
                for ($j = 0; $j < count($conditions); $j++) {
                    $valoresCampos[$j] = $conditions[$nomeCampos[$j]];
                }

                $sqlUpdate .= " WHERE ";

                for ($x = 0; $x < count($nomeCampos); $x++) {
                    if ($x != count($nomeCampos) - 1) {
                        $sqlUpdate .= $nomeCampos[$x] . " = :condition_$nomeCampos[$x] $conjunctions[$x] ";
                    } else {
                        $sqlUpdate .= $nomeCampos[$x] . " = :condition_$nomeCampos[$x]";
                    }
                }
            } else if ($conditions == null && $whereCriteria != null) {
                $sqlUpdate .= " WHERE " . $whereCriteria;
            }
            $sqlUpdate .= ";";
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
        return $sqlUpdate;
    }


    /**
     * @param $object
     * @param array $conditions
     * @param array $conjunctions
     * @return bool|string
     * @throws PhiberException
     */
    public static function delete($infos)
    {
        $tabela = $infos['table'];
        $conditions = isset($infos['conditions']) ? $infos['conditions'] : null;
        $conjunctions = isset($infos['conjunctions']) ? $infos['conjunctions'] : null;

        $whereCriteria = $infos['where'];


        try {
            $camposNome = [];
            $camposValores = [];

            $sql = "DELETE FROM $tabela ";
            if ($conditions != null && $whereCriteria == null) {
                $conditionsComIndexInt = array_keys($conditions);
                for ($i = 0; $i < count($conditions); $i++) {
                    $camposNome[$i] = $conditionsComIndexInt[$i];
                }

                for ($j = 0; $j < count($conditions); $j++) {
                    $camposValores[$j] = $conditions[$camposNome[$j]];
                }

                $sql .= "WHERE ";

                for ($x = 0; $x < count($camposNome); $x++) {
                    if ($x != count($camposNome) - 1) {
                        $sql .= $camposNome[$x] . " = :condition_$camposNome[$x] $conjunctions[$x] ";
                    } else {
                        $sql .= $camposNome[$x] . " = :condition_$camposNome[$x]";
                    }
                }
            } else if ($conditions == null && $whereCriteria != null) {
                $sql .= " WHERE " . $whereCriteria . " ";
            }
            return $sql . ";";
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
    }


    /**
     * @param $object
     * @param $conditions
     * @param bool $onlyFirst
     * @return array|bool|mixed
     * @throws PhiberException
     */
    public static function select($infos)
    {

        $tabela = $infos['table'];
        $campos = isset($infos['fields']) ? $infos['fields'] : ["*"];
        $conditions = isset($infos['conditions']) ? $infos['conditions'] : null;
        $conjunctions = isset($infos['conjunctions']) ? $infos['conjunctions'] : null;

        /* VARIAVEIS DA CRITERIA - COMEÇO*/
        $whereCriteria = $infos['where'];
        /* VARIAVEIS DA CRITERIA - FIM */

        /* LÓGICA - COMEÇO*/
        $camposNome = [];
        $camposValores = [];


        $campos = gettype($campos) == "array" ? implode(", ", $campos) : $campos;

        $sql = "SELECT " . $campos . " FROM $tabela ";


        if ($conditions != null && $whereCriteria == null) {
            $conditionsComIndexInt = array_keys($conditions);

            for ($i = 0; $i < count($conditions); $i++) {
                $camposNome[$i] = $conditionsComIndexInt[$i];
            }


            for ($j = 0; $j < count($conditions); $j++) {
                if ($conditions[$camposNome[$j]] != "") {
                    $camposValores[$j] = $conditions[$camposNome[$j]];
                }
            }
            $sql .= "WHERE ";
            for ($x = 0; $x < count($infos['conditions']); $x++) {

                if ($x != count($infos['conditions']) - 1) {
                    $sql .= $infos['conditions'][$x][0] . " " . $infos['conditions'][$x][1] . " :condition_" . $infos['conditions'][$x][0];
                    if ($conjunctions != null) {
                        $sql .= " " . $conjunctions[$x] . " ";
                    } else {
                        $sql .= " and ";
                    }
                } else {
                    $sql .= $infos['conditions'][$x][0] . " " . $infos['conditions'][$x][1] . " :condition_" . $infos['conditions'][$x][0];
                }
            }
        } else if ($conditions == null && $whereCriteria != null) {
            $sql .= " WHERE " . $whereCriteria . " ";
        }
        return $sql . ";";

    }

    /**
     * @param $query
     * @return bool
     * @throws PhiberException
     */
    public static function createQuery($query)
    {

        try {
            if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
                $pdo = self::getConnection()->prepare($query);
                if ($pdo->execute()) {
                    PhiberLogger::create("execution_query_success", "info", Execution::end());
                    return true;
                } else {
                    PhiberLogger::create("execution_query_failure", "error", Execution::end());
                    return false;
                }
            } else {
                return $query;
            }
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
    }
}

//ATUALMENTE
//PhiberQueryWriter::select("usuario",[],["nome"=>"Marcio","id"=>1],["and"]);


//IDEIA
//echo PhiberQueryWriter::select([
//    "table"=>"usuario",
//    "fields" => ["nome","id"],
//    "conditions"=>["nome"=>"Marcio", "id" => 1],
//    "conjunctions"=>"and"
//]);
