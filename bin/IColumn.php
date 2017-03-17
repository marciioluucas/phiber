<?php

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 13:36
 */
interface IColumn
{

    function get($prop);
    function set($prop,$value);
}