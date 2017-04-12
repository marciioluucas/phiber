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
class PhiberQueryBuilder implements IPhiberQueryBuilder
{

    /**
     * @param $object
     * @return mixed
     * @throws \Exception
     * Faz a criação de um registro no banco com os dados de um objeto.
     * Make an insert of an object in the database
     */
    public static function create($object)
    {
        Execution::start();
        \bin\TableMysql::sync($object);
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object));
            $campos = FuncoesReflections::pegaAtributosDoObjeto($object);
            $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($object);
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
            $camposValores = array_values($camposValores);
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
     * @param $object , $id
     * @param $id
     * @return mixed
     * @throws PhiberException
     */
    public static function update($object, $id)
    {
        TableMysql::sync($object);
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object));
            $campos = FuncoesReflections::pegaAtributosDoObjeto($object);
            $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($object);

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
            $camposNome = array_values($camposNome);
            $camposValores = array_values($camposValores);

            $sqlUpdate = "UPDATE $tabela SET ";

            for ($i = 0; $i < count($camposNome); $i++) {
                if ($i != count($camposNome) - 1) {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i] . ", ";
                } else {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i] . " WHERE pk_" . $tabela . " = " . $id;
                }
            }
            if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
                $pdo = self::getConnection()->prepare($sqlUpdate);
                for ($i = 0; $i < count($camposNome); $i++) {
                    $pdo->bindValue($camposNome[$i], $camposValores[$i]);
                }

                if ($pdo->execute()) {
                    PhiberLogger::create("execution_query_success", "info", $tabela, Execution::end());
                    return true;
                } else {
                    PhiberLogger::create("execution_query_failure", "error", $tabela, Execution::end());
                }
            } else {
                return $sqlUpdate;
            }
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("query_processor_error"));
        }
        return false;
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
            $nomeCampos = [];
            $conditionsComIndexInt = array_keys($conditions);
            for ($i = 0; $i < count($conditions); $i++) {
                $nomeCampos[$i] = $conditionsComIndexInt[$i];
            }
            $valoresCampos = [];
            for ($j = 0; $j < count($conditions); $j++) {
                $valoresCampos[$j] = $conditions[$nomeCampos[$j]];
            }
            $sql = "DELETE FROM $tabela ";
            if ($conditions != []) {
                $sql .= "WHERE ";
            }
            for ($x = 0; $x < count($nomeCampos); $x++) {
                if ($x != count($nomeCampos) - 1) {
                    $sql .= $nomeCampos[$x] . " = ? $conjuncoes[$x] ";
                } else {
                    $sql .= $nomeCampos[$x] . " = ?";
                }
            }

            if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
                $pdo = self::getConnection()->prepare($sql);
                for ($i = 1; $i <= count($nomeCampos); $i++) {
                    $pdo->bindValue($i, $valoresCampos[$i - 1]);
                }
                if ($pdo->execute()) {
                    PhiberLogger::create("execution_query_success", "info", $tabela, Execution::end());
                    return true;
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
            $nomeCampos = [];
            $conditionsComIndexInt = array_keys($conditions);
            for ($i = 0; $i < count($conditions); $i++) {
                $nomeCampos[$i] = $conditionsComIndexInt[$i];
            }
            $valoresCampos = [];
            for ($j = 0; $j < count($conditions); $j++) {
                $valoresCampos[$j] = $conditions[$nomeCampos[$j]];
            }
            $sql = "SELECT * FROM $tabela ";
            if ($conditions != []) {
                $sql .= "WHERE ";
            }
            for ($x = 0; $x < count($nomeCampos); $x++) {
                if ($x != count($nomeCampos) - 1) {
                    $sql .= $nomeCampos[$x] . " = ? $conjuncoes[$x] ";
                } else {
                    $sql .= $nomeCampos[$x] . " = ?";
                }
            }
            if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
                $pdo = self::getConnection()->prepare($sql);
                for ($i = 1; $i <= count($nomeCampos); $i++) {
                    $pdo->bindValue($i, $valoresCampos[$i - 1]);
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

            $nomeCampos = [];

            if ($conditions != null) {

                $conditionsComIndexInt = array_keys($conditions);

                for ($i = 0; $i < count($conditions); $i++) {
                    $nomeCampos[$i] = $conditionsComIndexInt[$i];
                }

                $valoresCampos = [];

                for ($j = 0; $j < count($conditions); $j++) {
                    if ($conditions[$nomeCampos[$j]] != "") {
                        $valoresCampos[$j] = $conditions[$nomeCampos[$j]];
                    }
                }

                $sql = "SELECT * FROM $tabela WHERE ";
                $nomeCamposNovo = [];
                for ($x = 0; $x < count($nomeCampos); $x++) {
                    if ($x != count($nomeCampos) - 1) {
                        if ($conditions[$nomeCampos[$x]] != "") {
                            if (count($valoresCampos) > 1) {
                                $sql .= $nomeCampos[$x] . " = ? and ";
                            } else {
                                $sql .= $nomeCampos[$x] . " = ?";
                            }
                            $nomeCamposNovo[$x] = $nomeCampos[$x];
                        }
                    } else {
                        if ($conditions[$nomeCampos[$x]] != "") {
                            $sql .= $nomeCampos[$x] . " = ?";
                            $nomeCamposNovo[$x] = $nomeCampos[$x];
                        }
                    }
                }
                $nomeCamposNovo = array_values($nomeCamposNovo);
                $pdo = self::getConnection()->prepare($sql);
                $valoresCampos = array_values($valoresCampos);

                for ($i = 1; $i <= count($nomeCamposNovo); $i++) {
                    $pdo->bindValue($i, $valoresCampos[$i - 1]);
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

//TODO: Ver se realmente precisa dessa de innerJoin
//    public static function innerJoin($object1, $object2, $conditions = null, $retornaSoPrimeiro = false, $campos = null)
//    {
//        $tabela1 = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object1));
//        $tabela2 = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($object2));
//
//        $nomeCampos = [];
//
//        if ($conditions != null) {
//            $conditionsComIndexInt = array_keys($conditions);
//            for ($i = 0; $i < count($conditions); $i++) {
//                $nomeCampos[$i] = $conditionsComIndexInt[$i];
//            }
//            $valoresCampos = [];
//            for ($j = 0; $j < count($conditions); $j++) {
//                if ($conditions[$nomeCampos[$j]] != "") {
//                    $valoresCampos[$j] = $conditions[$nomeCampos[$j]];
//                }
//            }
//            if ($campos == null) {
//                $sql = "SELECT * FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` where ";
//            } else {
//                $strCampos = "";
//                for ($i = 0; $i < count($campos); $i++) {
//                    if ($i != count($campos) - 1) {
//                        $strCampos .= $campos[$i] . ", ";
//                    } else {
//                        $strCampos .= $campos[$i] . " ";
//                    }
//                }
//                $sql = "SELECT $strCampos FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` where ";
//            }
//            $nomeCamposNovo = [];
//            for ($x = 0; $x < count($nomeCampos); $x++) {
//                if ($x != count($nomeCampos) - 1) {
//                    if ($conditions[$nomeCampos[$x]] != "") {
//                        if (count($valoresCampos) > 1) {
//                            $sql .= $nomeCampos[$x] . " = ? and ";
//                        } else {
//                            $sql .= $nomeCampos[$x] . " = ?";
//                        }
//                        $nomeCamposNovo[$x] = $nomeCampos[$x];
//                    }
//                } else {
//                    if ($conditions[$nomeCampos[$x]] != "") {
//                        $sql .= $nomeCampos[$x] . " = ?";
//                        $nomeCamposNovo[$x] = $nomeCampos[$x];
//                    }
//                }
//            }
//            $nomeCamposNovo = array_values($nomeCamposNovo);
//            $pdo = self::getConnection()->prepare($sql);
//            $valoresCampos = array_values($valoresCampos);
//
//            for ($i = 1; $i <= count($nomeCamposNovo); $i++) {
//                $pdo->bindValue($i, $valoresCampos[$i - 1]);
//            }
//            $pdo->execute();
//            if ($retornaSoPrimeiro) {
//                return $pdo->fetch(PDO::FETCH_ASSOC);
//            } else {
//                return $pdo->fetchAll(PDO::FETCH_ASSOC);
//            }
//        } else {
//            if ($campos == null) {
//
//                $sql = "SELECT * FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` ";
//            } else {
//                $strCampos = "";
//                for ($i = 0; $i < count($campos); $i++) {
//                    if ($i != count($campos) - 1) {
//                        $strCampos .= $campos[$i] . ", ";
//                    } else {
//                        $strCampos .= $campos[$i] . " ";
//                    }
//                }
//                $sql = "SELECT $strCampos FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` ";
//            }
//            $pdo = self::getConnection()->prepare($sql);
//            $pdo->execute();
//            if ($retornaSoPrimeiro) {
//                return $pdo->fetch(PDO::FETCH_ASSOC);
//            } else {
//                return $pdo->fetchAll(PDO::FETCH_ASSOC);
//            }
//        }
//    }

}