<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace Phiber\ORM\Exceptions;

use Exception;
use Phiber\Util\Internationalization;

/**
 * Classe reponsável por fazer as exceções personalizadas.
 * @package bin
 */
class PhiberException extends Exception
{
    /**
     * Referencia da mensagem a ser traduzida.
     * 
     * @var string
     */
    private $msgTranslateRef;

    /**
     * PhiberException constructor.
     * 
     * @param string $msgTranslateRef
     */
    public function __construct($msgTranslateRef)
    {
        $this->msgTranslateRef = $msgTranslateRef;
    }

    /**
     * Retorna a exceção personalizada, sobrescrevendo o metodo __toString
     * 
     * @return string
     */
    public function __toString()
    {
        return strtoupper(new Internationalization('phiber_exception')) .
            ": " . new Internationalization('line') . ": " . $this->getLine() . " " .
            new Internationalization('message') . ": " . new Internationalization($this->msgTranslateRef);
    }
}