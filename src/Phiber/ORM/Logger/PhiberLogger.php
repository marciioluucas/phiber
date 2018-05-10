<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\ORM\Logger;

use Phiber\Util\Internationalization;
use Phiber\Util\JsonReader;

/**
 * Classe responsável por criar o log do Phiber
 */
class PhiberLogger
{
    /**
     * Cria o log.
     * 
     * @param        $languageReference
     * @param string $level
     * @param string $objectName
     * @param null   $execTime
     */
    public static function create($languageReference, $level = 'info', $objectName = '', $execTime = null)
    {
        if (JsonReader::read(BASE_DIR.'/phiber_config.json')->phiber->log == 1 ? true : false) {
            $date = date('Y-m-d H:i:s');
            switch ($level) {
                case 'info':
                    $levelStr = strtoupper(new Internationalization("log_info"));
                    $msg = "\033[0;35m PHIBER LOG -> [$date] [$levelStr]: (" .
                    new Internationalization("reference") .
                    "=> \"$objectName\") " . new Internationalization($languageReference) .
                    " - ";
                    if($execTime != null){
                        $msg .=  "em " . $execTime . ".";
                    }
                    echo $msg . "\e[0m \n";
                    break;

                case 'warning':
                    $levelStr = strtoupper(new Internationalization("log_warning"));
                    $msg = "\033[1;33m PHIBER LOG -> [$date] [$levelStr]: (" .
                        new Internationalization("reference") .
                        "=> \"$objectName\") " . new Internationalization($languageReference) .
                        " - ";
                    if($execTime != null){
                        $msg .=  "em " . $execTime . ".";
                    }
                    echo $msg . "\e[0m \n";
                    break;

                case 'error':
                    $levelStr = strtoupper(new Internationalization("log_error"));
                    $msg = "\033[0;31m PHIBER LOG -> [$date] [$levelStr]: (" .
                        new Internationalization("reference") .
                        "=> \"$objectName\") " . new Internationalization($languageReference) .
                        " - ";
                    if($execTime != null){
                        $msg .=  "em " . $execTime . ".";
                    }
                    echo $msg . "\e[0m \n";
                    break;
            }
        }
    }
}
