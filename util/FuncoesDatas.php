<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\util;

/**
 * Class FuncoesDatas
 * @package util
 */
class FuncoesDatas
{

    /**
     * Converte para data Brasileira.
     * @param $data
     * @return false|string
     */
    public static final function converterDataParaBrasileira($data)
    {
        return date('d-m-Y', strtotime($data));
    }

    /**
     * Converte para data por extenso Brasileira.
     * @param $data
     * @return string
     */
    public static final function converterDataPorExtenso($data)
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return strftime('%A, %d de %B de %Y', strtotime($data));
    }
}