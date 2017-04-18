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
    private static $fieldsAndValues = [];

    /**
     * @return array
     */
    public static function getFieldsAndValues(): array
    {
        return self::$fieldsAndValues;
    }



    public static function eq($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        PhiberPersistence::add(self::$fieldsAndValues);
        return
            [
                "where" =>$param1 . " = :condition_" . $param1
            ];

    }

    public static function biggerThen($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => $param1 . " > :condition_" . $param1
        ];
    }

    public static function greaterThan($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => $param1 . " >= :condition_" . $param1,
        ];
    }

    public static function lessThen($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => $param1 . " < :condition_" . $param1,
        ];
    }

    public static function lessLike($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => $param1 . " <= :condition_" . $param1,
        ];
    }

    public static function like($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => $param1 . " LIKE %:condition_" . $param1 . "%",
        ];
    }

    public static function or ($condition1, $condition2)
    {

        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => "(" . $condition1['where'] . " or " . $condition2['where'] . ")",
        ];
    }

    public static function and ($condition1, $condition2)
    {

        PhiberPersistence::add(self::$fieldsAndValues);
        return [
            "where" => "(" . $condition1['where'] . " and " . $condition2['where'] . ")"
        ];
    }

    public static function fields($fields)
    {
        return ["fields" => $fields];
    }

    private static function addFieldsAndValues($field, $value)
    {
        self::$fieldsAndValues['fields_and_values'][$field] = $value;
    }

    public static function show()
    {
        return self::$fieldsAndValues;
    }

}