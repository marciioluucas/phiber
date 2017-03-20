<?php
require_once 'TableFactory.php';
require_once '../util/Annotations.php';

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 18:46
 */
class TableMySql extends TableFactory
{


    /**
     * @param $obj
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


            if ($i != count($atributosTabela) - 1) {

                $stringSql .= " , ";
            }

        }
        $stringSql .= ")";

        if (JsonReader::read("../phiber_config.json")->phiber->create_tables == 1 ? true : false) {
            $pdo = self::getConnection()->prepare($stringSql);
            if ($pdo->execute()) {
                return true;
            };
        } else {
            return $stringSql;
        }
        return false;
    }

    static function alter($obj)
    {
        // TODO: Implement alter() method.
    }

    static function drop($obj)
    {
        // TODO: Implement drop() method.
    }

    static function sync($obj)
    {
        // TODO: Implement sync() method.
    }


}

require_once '../test/Usuario.php';
$u = new Usuario();
include '../util/Execution.php';
TableMySQL::create($u);