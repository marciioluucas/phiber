<?php

/**
 * Created by PhpStorm.
 * User: Marcio
 * Date: 13/11/2016
 * Time: 10:21
 */
class FuncoesFinanceiras
{
    /*Parametros sao as moedas abreviadas como:
    * USD, EUR, ARS, GBP, BTC
    */
    public static final function cotacaoMoedasMundial($moeda)
    {
        $url = "http://api.fixer.io/latest?base=USD";

        $dadosSite = file_get_contents($url);
        $json = json_decode($dadosSite, true);

        $arrayRetorno = array(
            "moeda_base" => $json['base'],
            "data_consulta" => $json['date'],
            "valor" => $json['rates'][$moeda]
        );
        return $arrayRetorno;
    }

    public static final function convertReaisEmCentavos($reais)
    {
        return $reais * 100;
    }

    public static final function converteCentavosEmReais($centavos)
    {
        return $centavos / 100;
    }

    public static final function converteVirgulasReaisEmPontos($reais)
    {
        return str_replace(",", ".", $reais);
    }
}
