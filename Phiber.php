<?php
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 19:07
 */
namespace phiber;
use phiber\bin\PhiberPersistence;

class Phiber
{

    /**
     * @return PhiberPersistence
     */
    public static function openPersist()
    {
        return new PhiberPersistence();
    }

    public static function openCrypt()
    {
        return new PhiberCrypt();
    }

}