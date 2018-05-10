<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace phiber\util;

use phiber\util\Internationalization;

/**
 * Classe responsável por medir o tempo de execução das tarefas.
 * 
 * @package util
 */
class Execution
{
    /**
     * Define o tempo de inciação de execução nesta variável
     */
    private static $time;

    /**
     *  Procedimento responsável por calcular o tempo incial da execução
     */
    final public static function start()
    {
        self::$time = self::getTime();
    }

    /**
     * @return mixed
     */
    final public function getTime()
    {
        return microtime(true);
    }

    /**
     * Calcula a variação do tempo (Tempo final - Tempo inical) da execução e em seguida
     * formata o número para 6 digitos após a vírgula.
     * 
     * @return string
     */
    final public static function end()
    {
        $finalTime     = self::getTime();
        $execTime      = $finalTime - self::$time;
        $msgTranslated = new Internationalization("seconds");
        
        return number_format($execTime, 6) . " " . $msgTranslated;
    }
}
