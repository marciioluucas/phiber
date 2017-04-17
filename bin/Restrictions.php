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
                "where" => " " . $param1 . " = :condition_" . $param1 . " ",
            ];

    }

    public static function biggerThen($param1, $param2)
    {
        return [
            "where" => " " . $param1 . " > :condition_" . $param1 . " ",
        ];
    }

    public static function greaterThan($param1, $param2)
    {
        return [
            "where" => " " . $param1 . " >= :condition_" . $param1 . " ",
        ];
    }

    public static function lessThen($param1, $param2)
    {
        return [
            "where" => " " . $param1 . " < :condition_" . $param1 . " ",
        ];
    }

    public static function lessLike($param1, $param2)
    {
        return [
            "where" => " " . $param1 . " <= :condition_" . $param1 . " ",
        ];
    }

    public static function like($param1, $param2)
    {
        return [
            "where" => " " . $param1 . " <= :condition_" . $param1 . " ",
        ];
    }

    public static function or ($condition1, $condition2)
    {
        return [
            "where" => " (" . $condition1['where'] . " or " . $condition2['where'] . ") "
        ];
    }

    public static function and ($condition1, $condition2)
    {
        return [
            "where" => " (" . $condition1['where'] . " and " . $condition2['where'] . ") "
        ];
    }

    public static function fields($fields)
    {
        return ["fields" => $fields];
    }


}