<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\ORM;

use Phiber\ORM\Exceptions\NotImplementedException;
use Phiber\ORM\Interfaces\ICrypt;

/**
 * Classe responsável por administrar a encriptação/decriptação de dados.
 * @package bin
 */
class PhiberCrypt implements ICrypt
{
    /**
     * Encripta a informação passada no parâmetro
     * 
     * @todo Implement encrypt() method.
     * @param $information
     * @return mixed|void
     */
    static function encrypt($information)
    {
        throw new NotImplementedException();
    }

    /**
     * Decripta a informação passada no parâmetro
     * 
     * @todo Implement decrypt() method.
     * @param $information
     * @return mixed|void
     */
    static function decrypt($information)
    {
        throw new NotImplementedException();
    }
}
