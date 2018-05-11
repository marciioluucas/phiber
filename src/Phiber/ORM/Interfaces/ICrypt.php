<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\ORM\Interfaces;

/**
 * Interface ICrypt
 * @package bin
 */
interface ICrypt
{
    /**
     * Função responsável por fazer a encriptação da classe
     * 
     * @param $information
     * @return mixed
     */
    static function encrypt($information);

    /**
     * Função responsável por fazer a decriptação da classe
     * 
     * @param $information
     * @return mixed
     */
    static function decrypt($information);
}
