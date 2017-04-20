<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace util;




/**
 * A classe Annotations é responsável por pegar por reflexão os comentários de mapeamento das classes.
 * @package util
 */
class Annotations {

    /**
     * Recupera os comentários de mapeamentos das classes de modelo.
     * @param $obj
     *
     * Retorna um array de atributos
     * @return array
     */
    public static final function getAnnotation($obj){
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