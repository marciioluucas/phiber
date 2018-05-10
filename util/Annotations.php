<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace phiber\util;

/**
 * A classe Annotations é responsável por pegar por reflexão os comentários de mapeamento das classes.
 * 
 * @package util
 */
class Annotations 
{
    /**
     * Recupera os comentários de mapeamentos das classes de modelo.
     * 
     * @param $object
     * Retorna um array de atributos
     * @return array
     */
    final public static function getAnnotation($object)
    {
        $funReflec = new FuncoesReflections();
        
        $output = array();
        
        $pattern = '/@+_+[A-z]\w+=\w+/';
        
        $fullComments = $funReflec->retornaComentariosAtributos($object);
        $attribuites   = $funReflec->pegaAtributosDoObjeto($object);
        
        $annotationsList = [];
        
        $limit    = count($attribuites);
        $iterator = 0;
        for ($iterator; $iterator < $limit; $iterator++) {
            
            preg_match_all(
                $pattern,
                $fullComments[$attribuites[$iterator]],
                $output
            );

            $annotationsList[ $attribuites[$iterator] ] = $output[0];
        }

        return $annotationsList;
    }
}
