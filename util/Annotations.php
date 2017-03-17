<?php
require_once '../util/FuncoesReflections.php';
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
        $pattern = '/@+_+[A-z]\w+|=\w+/';
        $fullComments = FuncoesReflections::retornaComentariosDocumento($obj);

        preg_match_all($pattern,
            implode($fullComments,""),
            $out);



        return $out;
    }
}
require_once '../test/Usuario.php';

$u = new Usuario();

print_r(Annotations::getAnnotation($u));