<?php
require_once BASE_DIR .'/util/Internationalization.php';
require_once BASE_DIR .'/util/JsonReader.php';

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 3/20/17
 * Time: 9:10 AM
 */
class PhiberLogger
{

    public static function create($languageReference, $level = 'info', $objectName = '', $execTime = null)
    {
        if (JsonReader::read(BASE_DIR.'/phiber_config.json')->phiber->log == 1 ? true : false) {
            $date = date('Y-m-d H:i:s');
            $color = '';
            switch ($level) {
                case 'info':
                    $levelStr = strtoupper(Internationalization::translate("log_info"));
                    $msg = "\033[0;35m PHIBER LOG -> [$date] [$levelStr]: (" .
                    Internationalization::translate("reference") .
                    "=> \"$objectName\") " . Internationalization::translate($languageReference) .
                    " - ";
                    if($execTime != null){
                        $msg .=  "em " . $execTime . ".";
                    }
                    echo $msg . "\e[0m \n";
                    break;

                case 'warning':
                    $levelStr = strtoupper(Internationalization::translate("log_warning"));
                    $msg = "\033[1;33m PHIBER LOG -> [$date] [$levelStr]: (" .
                        Internationalization::translate("reference") .
                        "=> \"$objectName\") " . Internationalization::translate($languageReference) .
                        " - ";
                    if($execTime != null){
                        $msg .=  "em " . $execTime . ".";
                    }
                    echo $msg . "\e[0m \n";
                    break;

                case 'error':
                    $levelStr = strtoupper(Internationalization::translate("log_error"));
                    $msg = "\033[0;31m PHIBER LOG -> [$date] [$levelStr]: (" .
                        Internationalization::translate("reference") .
                        "=> \"$objectName\") " . Internationalization::translate($languageReference) .
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