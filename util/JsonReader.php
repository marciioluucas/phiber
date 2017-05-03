<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\util;

/**
 * Classe responsável por ler o arquivo de json especificado.
 * Class JsonReader
 * @package util
 */
class JsonReader
{

    private $arquivo;

    /**
     * JsonReader constructor.
     * @param $arquivo
     */
    public function __construct($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    public function read() {
        $info = file_get_contents($this->arquivo);
        $lendo = json_decode($info);
        return $lendo;
    }

}