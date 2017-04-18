<?php
namespace bin;
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 12/04/2017
 * Time: 08:31
 */
interface IPhiberQueryBuilder
{
    public static function create($infos);

    public static function update($infos);

    public static function delete($infos);

    public static function select($infos);
}