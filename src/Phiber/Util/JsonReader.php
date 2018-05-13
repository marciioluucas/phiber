<?php

namespace Phiber\Util;

/**
 * Classe responsável por ler o arquivo de json especificado.
 *
 * @package util
 */
class JsonReader
{
    /**
     * Caminho absoluto para o local do arquivo.
     *
     * @var string
     */
    private $file;

    /**
     * JsonReader constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Responsável por retornar o conteúdo do arquivo.
     *
     * @return array|boolean
     */
    public function read()
    {

        if ($content = file_get_contents($this->file)) {
            $read = json_decode($content);
            return $read;
        };
        return false;
    }
}
