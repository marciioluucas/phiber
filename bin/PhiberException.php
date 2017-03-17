<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 15:15
 */
class PhiberException extends Exception
{

    /**
     * PhiberException constructor.
     */
    public function __construct($message)
    {
        /** @var STRING $message */
        parent::__construct($message);
    }

    public function __toString()
    {
        $variableToReturn = "PHIBER TO-STRING -> " . __CLASS__ . ": [" . Internationalization::translate('line') . ":
         {" . $this->getLine() . "}, " . Internationalization::translate('message') . ": " . $this->getMessage() . "]";
        return $variableToReturn;
    }

    public function getPhiberException()
    {
        return strtoupper(Internationalization::translate('phiber_exception')) .
            ": " . Internationalization::translate('line') . ": " . $this->getLine() .
            Internationalization::translate('message') . ": " . $this->getMessage();
    }


}