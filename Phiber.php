<?php
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 19:07
 */
namespace phiber;
use bin\PhiberPersistence;

define("BASE_DIR",str_replace('\\', '/', dirname(__FILE__)));

class Phiber
{




    /**
     * @return PhiberPersistence
     */
    public static function openPersist()
    {
        new PhiberAutoload();
        return new PhiberPersistence();
    }

    public static function openCrypt()
    {
        return new PhiberCrypt();
    }

}
