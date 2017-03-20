<?php
require_once '../util/Internationalization.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 3/20/17
 * Time: 9:10 AM
 */
class Log
{

    public static function createLog($msg, $level = 'info')
    {

        $date = date('Y-m-d H:i:s');
        $color = '';
        switch ($level) {
            case 'info':
                $levelStr = strtoupper(Internationalization::translate("log_info"));
                echo "\033[0;35m PHIBER LOG -> [$date] [$levelStr]: $msg";
                break;

            case 'warning':
                $levelStr = strtoupper(Internationalization::translate("log_warning"));
                echo "\033[1;33m PHIBER LOG -> [$date] [$levelStr]: $msg";
                break;

            case 'error':
                $levelStr = strtoupper(Internationalization::translate("log_error"));
                echo $color."\033[0;31m PHIBER LOG -> [$date] [$levelStr]: $msg";
                break;
        }
    }
}

Log::createLog("Teste de log", 'warning');