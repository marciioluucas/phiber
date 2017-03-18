<?php
require_once 'TableFactory.php';
require_once 'Column.php';
require_once '../util/Annotations.php';

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 18:46
 */
class Table extends TableFactory
{
    private $column;

    /**
     * Table constructor.
     */
    public function __construct()
    {
        $this->column = new Column();
    }

    public static function createTable($obj)
    {
        $nomeTabela = FuncoesReflections::pegaNomeClasseObjeto($obj);
        $atributosTabela = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $annotationsTabela = Annotations::getAnnotation($obj);
        $arrFormatado = [];
        $stringSql = "CREATE TABLE ".strtolower($nomeTabela)." IF NOT EXISTS (";
        for ($i = 0; $i < count($atributosTabela); $i++) {
//            $stringSql .= $atributosTabela[$i] . " ";
            $stringSql .= $atributosTabela[$i] . " ";
            for ($j = 0; $j < count($annotationsTabela[$atributosTabela[$i]]); $j++) {
                $column = new Column();

// Esse aqui ta retornando as strings ja->   print_r($annotationsTabela[$atributosTabela[$i]][$j]);

                $arrAtual = explode("=", $annotationsTabela[$atributosTabela[$i]][$j]);
                for ($k = 0; $k < count($arrAtual) - 1; $k++) {
//                    echo count($arrAtual);

                    $arrFormatado[FuncoesString::substituiOcorrenciasDeUmaString($arrAtual[$k], "@_", "")] = $arrAtual[$k + 1];
                }

            }
            if(array_key_exists('type',$arrFormatado)){
//                    echo $arrFormatado['primaryKey'];
                    $stringSql .= " ".$arrFormatado['type']."";

                }
            else{
                $stringSql .= "";
            }


            if(array_key_exists('size',$arrFormatado)){
//                    echo $arrFormatado['primaryKey'];
                $stringSql .= "(".$arrFormatado['size'].") ";

            }
            else{
                $stringSql .= "";
            }

            //NOT NULL AQUI

            if(array_key_exists('notNull',$arrFormatado)){
                if ($arrFormatado['notNull'] === "true") {
                    $stringSql .= " NOT NULL ";
                } else {
                    $stringSql .= "";
                };
            }else{
                $stringSql .= "";
            }


            if(array_key_exists('primaryKey',$arrFormatado)){
                if ($arrFormatado['primaryKey'] === "true") {
                    $stringSql .= " PRIMARY KEY ";
                } else {
                    $stringSql .= "";
                };
            }else{
                $stringSql .= "";
            }

            if(array_key_exists('autoIncrement',$arrFormatado)){
                if ($arrFormatado['autoIncrement'] === "true") {
                    $stringSql .= " AUTO_INCREMENT ";
                } else {
                    $stringSql .= "";
                };
            }else{
                $stringSql .= "";
            }



            if($i != count($atributosTabela)-1){

                $stringSql .= " , ";
            }

            //AUTO INCREMENT AQUI


//            print_r($arrFormatado);

//            $stringSql .= $arrFormatado['autoIncrement'] == true ? " AUTO_INCREMENT " : "";

        }
        $stringSql .= ")";
        echo $stringSql;


        $example = "CREATE TABLE empregados (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
sobrenome VARCHAR(20), nome VARCHAR(20), telefone VARCHAR(20),  datanascimento DATE)";
    }

    public function alterTable()
    {

    }

    public function dropTable()
    {

    }

    function getTable()
    {
        return self::class;
    }
}

require_once '../test/Usuario.php';
$u = new Usuario();
Table::createTable($u);