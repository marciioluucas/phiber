<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

/**
 * Created by PhpStorm.
 * User: marci
 * Date: 24/04/2017
 * Time: 13:34
 */

namespace bin;


use util\JsonReader;

/**
 * Classe responsável por recuperar as informações de configuração do Phiber
 * @package bin
 */
class Config
{

    /**
     * @var JsonReader
     */
    private $phiberConfig;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->phiberConfig = new JsonReader(BASE_DIR . "/phiber_config.json");
    }

    /**
     * Retorna se é para executar as queries ou não.
     * @return bool
     */
    public function verifyExecuteQueries()
    {
        if ($this->phiberConfig->read()->phiber->execute_querys == 1) {
            return true;
        }
        return false;
    }
}