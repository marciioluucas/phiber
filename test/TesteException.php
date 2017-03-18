<?php
require_once '../util/Internationalization.php';
require_once '../bin/PhiberException.php';

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 18/03/17
 * Time: 00:55
 */
class TesteException
{
    public static function test()
    {
        try {
            return new PhiberException(Internationalization::translate("database_connection_error"));
        } catch (PhiberException $e) {
            return 0;
        }
    }
}

echo TesteException::test();