<?php
namespace bin;

use util\Internationalization;
use util\JsonReader;
use bin\PhiberException;


/**
 * Created by PhpStorm.
 * User: Marcio
 * Date: 19/10/2016
 * Time: 18:50
 */
class Link
{

    public static $instancia;

    public static function getConnection()
    {
        try {
            if (!isset(self::$instancia)) {
                $json = JsonReader::read(BASE_DIR . "/phiber_config.json");
                try {
                    self::$instancia = new \PDO(
                        $json->phiber->link->url,
                        $json->phiber->link->user,
                        $json->phiber->link->password,
                        array(\PDO::ATTR_PERSISTENT => $json->phiber->link->connection_cache == 1 ? true : false));
                } catch (PhiberException $e) {
                    throw new PhiberException(Internationalization::translate("database_connection_error"));
                }

            }
            return Link::$instancia;
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("database_connection_error"));
        }
    }
}