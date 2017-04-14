<?php
namespace bin;

use util\Execution;
use util\FuncoesReflections;
use util\FuncoesString;
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
    public static function create($tabela, $campos, $camposV)
    {

        try {
            $camposNome = [];
            $camposValores = [];
            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $camposNome[$i] = $campos[$i];
                }
            }

            for ($i = 0; $i < count($camposV); $i++) {
                if ($camposV[$i] != null) {
                    $camposValores[$i] = $camposV[$i];
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
    public static function update($tabela, $campos, $camposV, $conditions = [], $conjuncoes = [])
    {
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
            $camposNome = [];
            $camposNome = array_values($camposNome);
            $camposValores = array_values($camposValores);

            $sqlUpdate = "UPDATE $tabela SET ";

            for ($i = 0; $i < count($camposNome); $i++) {
                if ($i != count($camposNome) - 1) {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i] . ", ";
                } else {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i];
                    $conditionsComIndexInt = array_keys($conditions);
                    for ($i = 0; $i < count($conditions); $i++) {
                        $camposNome[$i] = $conditionsComIndexInt[$i];
                    }
                    $camposValores = [];
                    for ($j = 0; $j < count($conditions); $j++) {
                        $camposValores[$j] = $conditions[$camposNome[$j]];
                    }
                    if ($conditions != []) {
                        $sqlUpdate .= " WHERE ";
                    }
                    for ($x = 0; $x < count($camposNome); $x++) {
                        if ($x != count($camposNome) - 1) {
                            $sqlUpdate .= $camposNome[$x] . " = :" . $camposNome[$x] . " $conjuncoes[$x] ";
                        } else {
                            $sqlUpdate .= $camposNome[$x] . " = :" . $camposNome[$x] . ";";
                        }
                    }
                }
            }
            return $sqlUpdate;
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
    }


    /**
     * @param $object
     * @param array $conditions
     * @param array $conjuncoes
     * @return bool|string
     * @throws PhiberException
     */
    public static function delete($object, $conditions = [], $conjuncoes = [])
    {
        TableMysql::sync($object);
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object));
            $camposNome = [];
            $camposValores = [];
            $conditionsComIndexInt = array_keys($conditions);
            for ($i = 0; $i < count($conditions); $i++) {
                $camposNome[$i] = $conditionsComIndexInt[$i];
            }

            for ($j = 0; $j < count($conditions); $j++) {
                $camposValores[$j] = $conditions[$camposNome[$j]];
            }
            $sql = "DELETE FROM $tabela ";
            if ($conditions != []) {
                $sql .= "WHERE ";
            }
            for ($x = 0; $x < count($camposNome); $x++) {
                if ($x != count($camposNome) - 1) {
                    $sql .= $camposNome[$x] . " = :$camposNome[$x] $conjuncoes[$x] ";
                } else {
                    $sql .= $camposNome[$x] . " = :$camposNome[$x]";
                }
            }
            return $sql;
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
    }

    /**
     * @param $object
     * @param array $conditions
     * @param array $conjuncoes
     * @return string
     * @throws PhiberException
     */
    public static function rowCount($object, $conditions = [], $conjuncoes = [])
    {
        TableMysql::sync($object);
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object));
            $camposNome = [];
            $conditionsComIndexInt = array_keys($conditions);
            for ($i = 0; $i < count($conditions); $i++) {
                $camposNome[$i] = $conditionsComIndexInt[$i];
            }
            $camposValores = [];
            for ($j = 0; $j < count($conditions); $j++) {
                $camposValores[$j] = $conditions[$camposNome[$j]];
            }
            $sql = "SELECT * FROM $tabela ";
            if ($conditions != []) {
                $sql .= "WHERE ";
            }
            for ($x = 0; $x < count($camposNome); $x++) {
                if ($x != count($camposNome) - 1) {
                    $sql .= $camposNome[$x] . " = ? $conjuncoes[$x] ";
                } else {
                    $sql .= $camposNome[$x] . " = ?";
                }
            }
            if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
                $pdo = self::getConnection()->prepare($sql);
                for ($i = 1; $i <= count($camposNome); $i++) {
                    $pdo->bindValue($i, $camposValores[$i - 1]);
                }
                if ($pdo->execute()) {
                    PhiberLogger::create("execution_query_success", "info", $tabela, Execution::end());
                    return $pdo->rowCount();
                } else {
                    PhiberLogger::create("execution_query_failure", "error", $tabela, Execution::end());
                }
            } else {
                return $sql;
            }
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
        return false;
    }


    /**
     * @param $object
     * @param $conditions
     * @param bool $onlyFirst
     * @return array|bool|mixed
     * @throws PhiberException
     */
    public static function searchWithConditions($object, $conditions, $onlyFirst = false)
    {
        TableMysql::sync($object);
        try {

            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object));
            $camposNome = [];
            $camposValores = [];

            if ($conditions != null) {

                $conditionsComIndexInt = array_keys($conditions);

                for ($i = 0; $i < count($conditions); $i++) {
                    $camposNome[$i] = $conditionsComIndexInt[$i];
                }


                for ($j = 0; $j < count($conditions); $j++) {
                    if ($conditions[$camposNome[$j]] != "") {
                        $camposValores[$j] = $conditions[$camposNome[$j]];
                    }
                }

                $sql = "SELECT * FROM $tabela WHERE ";
                $camposNomeNovo = [];
                for ($x = 0; $x < count($camposNome); $x++) {
                    if ($x != count($camposNome) - 1) {
                        if ($conditions[$camposNome[$x]] != "") {
                            if (count($camposValores) > 1) {
                                $sql .= $camposNome[$x] . " = ? and ";
                            } else {
                                $sql .= $camposNome[$x] . " = ?";
                            }
                            $camposNomeNovo[$x] = $camposNome[$x];
                        }
                    } else {
                        if ($conditions[$camposNome[$x]] != "") {
                            $sql .= $camposNome[$x] . " = ?";
                            $camposNomeNovo[$x] = $camposNome[$x];
                        }
                    }
                }
                $camposNomeNovo = array_values($camposNomeNovo);
                $pdo = self::getConnection()->prepare($sql);
                $camposValores = array_values($camposValores);

                for ($i = 1; $i <= count($camposNomeNovo); $i++) {
                    $pdo->bindValue($i, $camposValores[$i - 1]);
                }
                $pdo->execute();

                if ($onlyFirst) {
                    return $pdo->fetch(PDO::FETCH_ASSOC);
                } else {
                    return $pdo->fetchAll(PDO::FETCH_ASSOC);
                }
            } else {
                $sql = "SELECT * FROM $tabela";
                $pdo = self::getConnection()->prepare($sql);
                if ($pdo->execute()) {
                    PhiberLogger::create("execution_query_success", "info", $tabela, Execution::end());
                    return true;
                } else {
                    PhiberLogger::create("execution_query_failure", "error", $tabela, Execution::end());
                }
                if ($onlyFirst) {
                    return $pdo->fetch(PDO::FETCH_ASSOC);
                } else {
                    return $pdo->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
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