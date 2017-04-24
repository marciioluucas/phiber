<?php

/**
 * Created by PhpStorm.
 * User: marci
 * Date: 13/04/2017
 * Time: 14:21
 */
class PhiberAutoload
{
    function __construct()
    {
        spl_autoload_register(function ($class) {
            include_once(str_replace('\\', '/', $class . '.php'));
        });
    }
}