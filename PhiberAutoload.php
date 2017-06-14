<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber;
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 13/04/2017
 * Time: 14:21
 */
class PhiberAutoload
{
    public function __construct()
    {
        spl_autoload_register(function ($class) {
            include_once(str_replace('\\', '/', $class . '.php'));
        });
    }
}