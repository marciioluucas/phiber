<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace bin;

use util\Internationalization;


/**
 * Classe reponsável por fazer as exceções personalizadas.
 * @package bin
 */
class PhiberException extends \Exception
{
    /**
     * Referencia da mensagem a ser traduzida.
     * @var string
     */
    private $messageTranslateReference;

    /**
     * PhiberException constructor.
     * @param string $messageTranslateReference
     */
    public function __construct($messageTranslateReference)
    {
        $this->messageTranslateReference = $messageTranslateReference;
    }

    /**
     * Retorna a exceção personalizada, sobrescrevendo o metodo __toString
     * @return string
     */
    public function __toString()
    {
        return strtoupper(Internationalization::translate('phiber_exception')) .
            ": " . Internationalization::translate('line') . ": " . $this->getLine() . " " .
            Internationalization::translate('message') . ": " . Internationalization::translate($this->messageTranslateReference);
    }


}