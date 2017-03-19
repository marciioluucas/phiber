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

    /**
     * @return mysqli|PDO
     * @throws PhiberException
     */
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
                        array(PDO::ATTR_PERSISTENT => $json->phiber->link->connection_cache == 1 ? true : false));
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