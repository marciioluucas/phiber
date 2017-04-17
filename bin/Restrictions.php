<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 16/04/2017
 * Time: 22:19
 */

namespace bin;


class Restrictions
{


    public static function eq($param1, $param2)
    {
        return
            [
                "sql" => " " . $param1 . " = :condition_" . $param1 . " ",
                "value" => $param2
            ];
    }

    public static function biggerThen($param1, $param2)
    {
        return " " . $param1 . " > :condition_" . $param1 . " ";
    }

    public static function greaterThan()
    {

    }

    public static function lessThen()
    {

    }

    public static function lessLike()
    {

    }

    public static function like()
    {

    }

    public static function or ()
    {

    }

    public static function and ()
    {

    }


}