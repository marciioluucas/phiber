<?php

/**
 * Created by PhpStorm.
 * User: Marcio
 * Date: 13/11/2016
 * Time: 12:04
 */
class FuncoesDatas
{
    public static final function converterDataParaBrasileira($data)
    {
        return date('d-m-Y', strtotime($data));
    }

    public static final function converterDataPorExtenso($data)
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return strftime('%A, %d de %B de %Y', strtotime($data));
    }
}