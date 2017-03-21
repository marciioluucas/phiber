<?php
require_once '../util/FuncoesReflections.php';
require_once '../util/FuncoesString.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 20:45
 */


//echo $a;
//print_r($out);

class Annotations {

    public static function getAnnotation($obj){
        $out = array();
        $pattern = '/@+_+[A-z]\w+=\w+/';
        $fullComments = FuncoesReflections::retornaComentariosAtributos($obj);
        $attributos = FuncoesReflections::pegaAtributosDoObjeto($obj);
        $test =[];
        for($i = 0; $i < count($attributos); $i++){
            preg_match_all($pattern,
                $fullComments[$attributos[$i]],
                $out);
            $test[$attributos[$i]] = $out[0];


        }



        return  $test;

    }
}
//require_once '../test/Usuario.php';
//
//$u = new Usuario();
//
//print_r(Annotations::getAnnotation($u));