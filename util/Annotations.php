<?php
require_once '../util/FuncoesReflections.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 20:45
 */

//$out = array();
//$pattern = '/@[A-z]+[ ]{0,1}=[ ]{0,1}[a-z0-9]+/';
//$subject = '@type= integer';
//
//$a = preg_match_all($pattern,
//    $subject,
//    $out);
//echo $a;
//print_r($out);

class Annotations {

    public static function getAnnotation($obj){
        return FuncoesReflections::retornaComentariosDocumento($obj);
    }
}
require_once '../test/Usuario.php';
$u = new Usuario();

print_r(Annotations::getAnnotation($u));