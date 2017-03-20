<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 3/20/17
 * Time: 10:47 AM
 */
interface ICrypt
{
    static function encrypt($information);
    static function decrypt($information);
}