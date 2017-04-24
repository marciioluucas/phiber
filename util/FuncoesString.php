<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace util;

/**
 *
 * Classe responsável por manipular strings.
 * @package util
 */
class FuncoesString
{
    /**
     * Passa para caixa alta
     * @param $string
     * @return string
     */
    public function paraCaixaAlta($string)
    {
        return strtoupper($string);
    }

    /**
     * Passa para caixa baixa
     * @param $string
     * @return string
     */
    public function paraCaixaBaixa($string)
    {
        return strtolower($string);
    }

    /**
     * Verifica se a string é existente, se caso não for, retornará false.
     * @param $string
     * @param $stringBuscada
     * @return bool
     */
    public function verificaStringExistente($string, $stringBuscada)
    {
        if (strpos($string, $stringBuscada) !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $string
     * @return string
     */
    public function passarPrimeiraLetraParaCaixaAlta($string)
    {
        return ucfirst($string);
    }

    /**
     * Separa uma string.
     * @param $string
     * @param $posInicial
     * @param null $posFinal
     * @return string
     */
    public function separaString($string, $posInicial, $posFinal = null)
    {
        if ($posFinal == null) {
            return substr($string, $posInicial - 1);
        } else {
            return substr($string, $posInicial - 1, ($posFinal - 1) * (-1));
        }
    }


    /**
     * Retorna a posição da ocorrencia, se caso não existir, a função retornará false.
     * @param $string
     * @param $strBusca
     * @return int
     */
    public function pegaPosStringDeterminada($string, $strBusca)
    {
        $tamanhoStrBusca = strlen($strBusca);
        return stripos($string, $strBusca) + $tamanhoStrBusca + 1;
    }

    /**
     * Substitui ocorrencias de uma string. Se caso a ocorrencia não existir, a função retornará false.
     * @param $string
     * @param $strBusca
     * @param $substituicao
     * @return mixed
     */
    public function substituiOcorrenciasDeUmaString($string, $strBusca, $substituicao) {
        return str_replace($strBusca,$substituicao,$string);
    }

}
