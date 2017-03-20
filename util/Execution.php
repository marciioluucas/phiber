<?php
require_once 'Internationalization.php';

class Execution
{

    private static $time;

    function getTime()
    {
        return microtime(TRUE);
    }

    /* Calculate start time */
    static function start()
    {
        self::$time = self::getTime();
    }

    static function end()
    {
        $finalTime = self::getTime();
        $execTime = $finalTime - self::$time;
        return number_format($execTime, 6) . " " .Internationalization::translate("seconds");
    }
}

