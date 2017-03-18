<?php
require_once '../util/JsonReader.php';
require_once '../util/Internationalization.php';
require_once 'PhiberException.php';

/**
 * Created by PhpStorm.
 * User: Marcio
 * Date: 19/10/2016
 * Time: 18:50
 */
class Link
{
    /**
     * @var mysqli
     */
    public static $instancia;

    public static function getConnection()
    {
        try {
            if (!isset(self::$instancia)) {
                $json = JsonReader::read("../phiber_config.json");
                try{
                    self::$instancia = new PDO(
                        $json->phiber->link->url,
                        $json->phiber->link->user,
                        $json->phiber->link->password,
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                }catch(PhiberException $e){
                    throw new PhiberException(Internationalization::translate("database_connection_error"));
                }

            }
            return self::$instancia;
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("database_connection_error"));
        }
    }
}