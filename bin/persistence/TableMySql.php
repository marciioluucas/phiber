<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace bin\persistence;


use bin\factories\TableFactory;
use util\Annotations;
use util\FuncoesReflections;
use util\FuncoesString;
use util\JsonReader;


/**
 * Classe responsável por criar as tabelas do banco
 * @package bin
 */
class TableMySql extends TableFactory
{


    /**
     * Sincroniza o banco com o código em tempo de instanciação.
     * @param Object $obj
     * @return mixed|void
     */
    static function sync($obj)
    {

        if (self::exists($obj)) {

            self::drop($obj);
            self::alter($obj);
        } else {

            self::create($obj);
        }
    }

    /**
     * Verifica se a tabela existe, se caso não existir, a função retornará false.
     * @param Object $obj
     * @return bool|string
     */
    static function exists($obj)
    {
        $tabela = strtolower(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $schema = JsonReader::read(BASE_DIR . '/phiber_config.json')->phiber->link->database_name;
        $sql = "SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ?";
        if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
            $pdo = self::getConnection()->prepare($sql);
            $pdo->bindValue(1, $schema);
            $pdo->bindValue(2, $tabela);
            $pdo->execute();
            if ($pdo->rowCount() > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            return $sql;
        }
    }

    /**
     * Deleta a tabela do banco de dados.
     * @param Object $obj
     * @return bool|string
     */
    static function drop($obj)
    {
        $tabela = strtolower(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $atributosObjeto = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $columnsTabela = self::columns($tabela);
        $arrayCamposTabela = [];
        for ($i = 0; $i < count($columnsTabela); $i++) {
            array_push($arrayCamposTabela, $columnsTabela[$i]['Field']);
        }
        $arrayDiff = array_diff($arrayCamposTabela, $atributosObjeto);
        $arrayDiff = array_values($arrayDiff);
        $sqlDrop = "ALTER TABLE $tabela \n";
        for ($j = 0; $j < count($arrayDiff); $j++) {
            if ($j != count($arrayDiff) - 1) {
                $sqlDrop .= "DROP " . $arrayDiff[$j] . ", ";
            } else {
                $sqlDrop .= "DROP " . $arrayDiff[$j] . ";";
            }
        }
        if (JsonReader::read(BASE_DIR."/phiber_config.json")->phiber->code_sync == 1 ? true : false) {
            $pdo = self::getConnection()->prepare($sqlDrop);
            if ($pdo->execute()) {
                return true;
            };
        } else {
            return $sqlDrop;
        }
        return false;
    }

    /**
     * Mostra as colunas daquela tabela.
     * @param String $table
     * @return array|bool
     */
    static function columns($table)
    {
        $sql = "show columns from " . strtolower($table);

        if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->execute_querys == 1 ? true : false) {
            $pdo = self::getConnection()->prepare($sql);
            if ($pdo->execute()) {
                return $pdo->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Altera a tabela do banco
     * @param Object $obj
     * @return bool|string
     */
    static function alter($obj)
    {
        $tabela = strtolower(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $atributosTabela = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $annotationsTabela = Annotations::getAnnotation($obj);
        $arrFormatado = [];
        $arrFinal = [];
        $stringAlterTable = "";
        for ($i = 0; $i < count($atributosTabela); $i++) {
            for ($j = 0; $j < count($annotationsTabela[$atributosTabela[$i]]); $j++) {
                $arrAtual = explode("=", $annotationsTabela[$atributosTabela[$i]][$j]);
                for ($k = 0; $k < count($arrAtual) - 1; $k++) {
                    $arrFormatado[FuncoesString::substituiOcorrenciasDeUmaString($arrAtual[$k], "@_", "")] = $arrAtual[$k + 1];
                }
            }
            $arrFinal[$i] = $arrFormatado;
        }


        $stringAlterTable .= "ALTER TABLE $tabela \n";
        $primKey = false;
        $columnPrimaryKey = "";

        $columnsTabela = self::columns($tabela);

        $arrayCamposTabela = [];
        for ($i = 0; $i < count($columnsTabela); $i++) {
            array_push($arrayCamposTabela, $columnsTabela[$i]['Field']);
        }
        $arrayDiff = array_diff($atributosTabela, $arrayCamposTabela);
        $arrayDiff = array_values($arrayDiff);

        $stringSql = $stringAlterTable;
        for ($j = 0; $j < count($arrayDiff); $j++) {
            if ($j != count($arrayDiff) - 1) {
                $stringSql .= "ADD " . $arrayDiff[$j];
            } else {
                $stringSql .= "ADD " . $arrayDiff[$j];
            }
            if (array_key_exists('type', $arrFormatado)) {
//                    echo $arrFormatado['primaryKey'];
                $stringSql .= " " . $arrFormatado['type'] . "";

            } else {
                $stringSql .= "";
            }


            if (array_key_exists('size', $arrFormatado)) {
//                    echo $arrFormatado['primaryKey'];
                $stringSql .= "(" . $arrFormatado['size'] . ") ";

            } else {
                $stringSql .= "";
            }

            //NOT NULL AQUI

            if (array_key_exists('notNull', $arrFormatado)) {
                if ($arrFormatado['notNull'] === "true") {
                    $stringSql .= " NOT NULL ";
                } else {
                    $stringSql .= "";
                };
            } else {
                $stringSql .= "";
            }


            if (array_key_exists('primaryKey', $arrFormatado)) {
                if ($arrFormatado['primaryKey'] === "true") {
                    $stringSql .= " PRIMARY KEY ";
                } else {
                    $stringSql .= "";
                };
            } else {
                $stringSql .= "";
            }

            if (array_key_exists('autoIncrement', $arrFormatado)) {
                if ($arrFormatado['autoIncrement'] === "true") {
                    $stringSql .= " AUTO_INCREMENT ";
                } else {
                    $stringSql .= "";
                };
            } else {
                $stringSql .= "";
            }

            if (JsonReader::read(BASE_DIR."/phiber_config.json")->phiber->code_sync == 1 ? true : false) {
                $pdo = self::getConnection()->prepare($stringSql);
                $pdo->execute();
            }
        }


        $columnsTabela = self::columns($tabela);
        for ($i = 0; $i < count($arrFinal); $i++) {
            if ($columnsTabela[$i]['Field'] != $atributosTabela[$i]) {
                $stringAlterTable .= "CHANGE `" . $columnsTabela[$i]['Field'] . "` `$atributosTabela[$i]` ";
                $stringAlterTable .= strtoupper($arrFinal[$i]['type']) . "(" . $arrFinal[$i]['size'] . ")\n";

                if ($arrFinal[$i])

                    if ($i != count($arrFinal) - 1) {
                        $stringAlterTable .= ", \n";
                    }
            } else {
                $stringAlterTable .= "CHANGE  `$atributosTabela[$i]` `$atributosTabela[$i]` ";
//                $strTamanhoTypeTabela = strstr($columnsTabela[$i]['Type'], '(', false);
//                $typeTabela = strstr($columnsTabela[$i]['Type'], '(', true);
//                $tamanhoTypeTabela = substr($strTamanhoTypeTabela, 1, stripos($strTamanhoTypeTabela, ')') - 1);


//                if ($typeTabela != $arrFinal[$i]['type'] || $tamanhoTypeTabela != $arrFinal[$i]['size']) {
//                    if ($typeTabela != $arrFinal[$i]['type'] && $tamanhoTypeTabela != $arrFinal[$i]['size']) {
                $stringAlterTable .= strtoupper($arrFinal[$i]['type']) . "(" . $arrFinal[$i]['size'] . ")";
//                    } else {
//                        if ($tamanhoTypeTabela != $arrFinal[$i]['size']) {
//                            $stringAlterTable .= strtoupper($arrFinal[$i]['type']) . "(" . $arrFinal[$i]['size'] . ")";
//                        }
//
//                    }
//                }
                $respIfNotNull = $columnsTabela[$i]['Null'] == 'NO' ? 'false' : 'true';
//                echo $arrFinal[$i]['notNull'];
                if ($arrFinal[$i]['notNull'] == $respIfNotNull) {
                    $stringAlterTable .= " NOT NULL ";
                } else {
                    $stringAlterTable .= " NULL ";
                }

//                $respIfDefault =  ? $arrFinal[$i]['default'] : 'default_not_exists';
                if (array_key_exists('default', $arrFinal[$i]) && $arrFinal[$i]['default'] != "none") {
                    $stringAlterTable .= "DEFAULT '" . $arrFinal[$i]['default'] . "'";
                }

                if (array_key_exists('autoIncrement', $arrFinal[$i]) && $arrFinal[$i]['autoIncrement'] != "false") {
                    $stringAlterTable .= " AUTO_INCREMENT ";
                }

                $stringAlterTable .= ", \n";


                if ($arrFinal[$i]['primaryKey'] == "true" and $primKey != true) {
                    $primKey = true;
                    $columnPrimaryKey = $atributosTabela[$i];
                }
                if ($i == count($arrFinal) - 1) {
                    if ($primKey) {
                        $stringAlterTable .= " DROP PRIMARY KEY, ADD PRIMARY KEY(`$columnPrimaryKey`);";
                    } else {
                        $stringAlterTable .= " DROP PRIMARY KEY";
                    }
                }
            }
        }
        if (JsonReader::read(BASE_DIR."/phiber_config.json")->phiber->code_sync == 1 ? true : false) {
            $pdo = self::getConnection()->prepare($stringAlterTable);
            if ($pdo->execute()) {
                return true;
            };
        } else {
            return $stringAlterTable;
        }
        return false;
    }

    /**
     * Cria a tabela
     * @param Object $obj
     * @return bool|string
     */
    public static function create($obj)
    {

        $nomeTabela = FuncoesReflections::pegaNomeClasseObjeto($obj);
        $atributosTabela = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $annotationsTabela = Annotations::getAnnotation($obj);
        $arrFormatado = [];
        $stringSql = "C" . "REATE TABLE IF NOT EXISTS `" . strtolower($nomeTabela) . "` (";
        for ($i = 0; $i < count($atributosTabela); $i++) {
//            $stringSql .= $atributosTabela[$i] . " ";
            $stringSql .= $atributosTabela[$i] . " ";
            for ($j = 0; $j < count($annotationsTabela[$atributosTabela[$i]]); $j++) {


// Esse aqui ta retornando as strings ja->   print_r($annotationsTabela[$atributosTabela[$i]][$j]);

                $arrAtual = explode("=", $annotationsTabela[$atributosTabela[$i]][$j]);
                for ($k = 0; $k < count($arrAtual) - 1; $k++) {
//                    echo count($arrAtual);

                    $arrFormatado[FuncoesString::substituiOcorrenciasDeUmaString($arrAtual[$k], "@_", "")] = $arrAtual[$k + 1];
                }

            }
            if (array_key_exists('type', $arrFormatado)) {
//                    echo $arrFormatado['primaryKey'];
                $stringSql .= " " . $arrFormatado['type'] . "";

            } else {
                $stringSql .= "";
            }


            if (array_key_exists('size', $arrFormatado)) {
                if ($arrFormatado['size'] != 'none') {
                    $stringSql .= "(" . $arrFormatado['size'] . ") ";
                }

            } else {
                $stringSql .= "";
            }

            //NOT NULL AQUI

            if (array_key_exists('notNull', $arrFormatado)) {
                if ($arrFormatado['notNull'] === "true") {
                    $stringSql .= " NOT NULL ";
                } else {
                    $stringSql .= "";
                };
            } else {
                $stringSql .= "";
            }


            if (array_key_exists('primaryKey', $arrFormatado)) {
                if ($arrFormatado['primaryKey'] === "true") {
                    $stringSql .= " PRIMARY KEY ";
                } else {
                    $stringSql .= "";
                };
            } else {
                $stringSql .= "";
            }

            if (array_key_exists('autoIncrement', $arrFormatado)) {
                if ($arrFormatado['autoIncrement'] === "true") {
                    $stringSql .= " AUTO_INCREMENT ";
                } else {
                    $stringSql .= "";
                };
            } else {
                $stringSql .= "";
            }


            if ($i != count($atributosTabela) - 1) {

                $stringSql .= " , ";
            }

        }
        $stringSql .= ") ENGINE = InnoDB;";
        echo $stringSql;
        if (JsonReader::read(BASE_DIR . "/phiber_config.json")->phiber->code_sync == 1 ? true : false) {
            $pdo = self::getConnection()->prepare($stringSql);
            if ($pdo->execute()) {
                return true;
            };
        } else {
            return $stringSql;
        }
        return false;
    }
}
