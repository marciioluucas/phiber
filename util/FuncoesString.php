<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace phiber\util;

/**
 *
 * Classe responsável por manipular strings.
 * 
 * @package util
 */
class FuncoesString
{
    /**
     * Passa para caixa alta
     * 
     * @param  string $string
     * @return string
     */
    public function paraCaixaAlta($string)
    {
        return strtoupper($string);
    }

    /**
     * Passa para caixa baixa
     * 
     * @param  string $string
     * @return string
     */
    public function paraCaixaBaixa($string)
    {
        return strtolower($string);
    }

    /**
     * Verifica se a string é existente, se caso não for, retornará false.
     * 
     * @param  string $string
     * @param  string $stringBuscada
     * @return bool
     */
    public function verificaStringExistente($string, $stringBuscada)
    {
        if (strpos($string, $stringBuscada) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Passa a primeira letra da string para caixa alta.
     * 
     * @param  string $string
     * @return string
     */
    public function passarPrimeiraLetraParaCaixaAlta($string)
    {
        return ucfirst($string);
    }

    /**
     * Separa uma string.
     * 
     * @param  string $string
     * @param  int    $posicaoInicial
     * @param  int    $posicaoFinal   default null
     * @return string
     */
    public function separaString($string, $posicaoInicial, $posicaoFinal = null)
    {
        if ($posicaoFinal == null) {
            return substr($string, $posicaoInicial - 1);
        }

        return substr($string, $posicaoInicial - 1, ($posicaoFinal - 1) * (-1));
    }


    /**
     * Retorna a posição da ocorrencia, se caso não existir, a função retornará false.
     * 
     * @param  string $string
     * @param  string $stringBusca
     * @return int
     */
    public function pegaPosStringDeterminada($string, $stringBusca)
    {
        $tamanhoStrBusca = strlen($stringBusca);
     
        return stripos($string, $stringBusca) + $tamanhoStrBusca + 1;
    }

    /**
     * Substitui ocorrencias de uma string. Se caso a ocorrencia não existir, a função retornará false.
     * 
     * @param  string $string
     * @param  string $stringBusca
     * @param  string $substituicao
     * @return mixed
     */
    public function substituiOcorrenciasDeUmaString($string, $stringBusca, $substituicao)
    {
        return str_replace($stringBusca, $substituicao, $string);
    }
}
