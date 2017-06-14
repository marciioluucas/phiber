<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\util;




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
    public final static function getAnnotation($obj)
    {
        $funReflec = new FuncoesReflections();
        $out = array();
        $pattern = '/@+_+[A-z]\w+=\w+/';
        $fullComments = $funReflec->retornaComentariosAtributos($obj);
        $attributos = $funReflec->pegaAtributosDoObjeto($obj);
        $ann = [];
        for($i = 0; $i < count($attributos); $i++){
            preg_match_all($pattern,
                $fullComments[$attributos[$i]],
                $out);
            $ann[$attributos[$i]] = $out[0];


        }
        return $ann;
    }
}