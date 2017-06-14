<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\util;

/**
 * Classe responsável por medir o tempo de execução das tarefas.
 * @package util
 */
class Execution
{

    /**
     * @var
     * Define o tempo de inciação de execução nesta variável
     */
    private static $time;

    /**
     * @return mixed
     */
    public final function getTime()
    {
        return microtime(TRUE);
    }


    /**
     *  Procedimento responsável por calcular o tempo incial da execução
     */
    public final static function start()
    {
        self::$time = self::getTime();
    }

    /**
     * Calcula a variação do tempo (Tempo final - Tempo inical) da execução e em seguida
     * formata o número para 6 digitos após a vírgula.
     * @return string
     */
    public final static function end()
    {
        $finalTime = self::getTime();
        $execTime = $finalTime - self::$time;
        $msgTranslated = new Internationalization("seconds");
        return number_format($execTime, 6) . " " . $msgTranslated;
    }
}

