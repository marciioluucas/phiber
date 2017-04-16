<?php
namespace bin;

use util\Internationalization;

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 15:15
 */
class PhiberException extends \Exception
{
    private $messageTranslateReference;

    /**
     * PhiberException constructor.
     */
    public function __construct($messageTranslateReference)
    {
        $this->messageTranslateReference = $messageTranslateReference;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strtoupper(Internationalization::translate('phiber_exception')) .
            ": " . Internationalization::translate('line') . ": " . $this->getLine() . " " .
            Internationalization::translate('message') . ": " . Internationalization::translate($this->messageTranslateReference);
    }


}