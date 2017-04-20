<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace util;

/**
 * Classe responsável por ler o arquivo de json especificado.
 * Class JsonReader
 * @package util
 */
class JsonReader
{
    /**
     * Lê o arquivo JSON
     * @param $arquivo
     * @return mixed
     */
    public static function read($arquivo){
            $info = file_get_contents($arquivo);
            $lendo = json_decode($info);
            return $lendo;
        }
}