<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\ORM;

use Phiber\Util\JsonReader;

/**
 * Classe responsável por recuperar as informações de configuração do Phiber
 * 
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

        if (!empty(glob(dirname(__DIR__, 4) . "/phiber_config.json")[0])) {
            $this->phiberConfig = new JsonReader(glob(dirname(__DIR__, 4) . "/phiber_config.json")[0]);
        }
    }

    /**
     * Retorna se é para executar as queries ou não.
     * 
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