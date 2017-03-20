<?php
require_once 'PhiberPersistence.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 19:07
 */
class Phiber
{

    /**
     * @return PhiberPersistence
     */
    public static function openPersist()
    {
        //TODO:: Aqui vai ficar a parada de criar tabela ou nao.

        return new PhiberPersistence();
    }

    public static function crypt(){
        return new PhiberCrypt();
    }

}