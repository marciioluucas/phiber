<?php
namespace phiber\bin;

use phiber\PDO;
use phiber\util\Internationalization;
use phiber\util\JsonReader;


/**
 * Created by PhpStorm.
 * User: Marcio
 * Date: 19/10/2016
 * Time: 18:50
 */
class Link
{

    public static function getConnection()
    {
        $instancia = null;
        try {
            if (!$instancia != null) {
                $json = JsonReader::read(BASE_DIR . "/phiber_config.json");
                try {
                   $instancia = new PDO(
                        $json->phiber->link->url,
                        $json->phiber->link->user,
                        $json->phiber->link->password,
                        array(PDO::ATTR_PERSISTENT => $json->phiber->link->connection_cache == 1 ? true : false));
                } catch (PhiberException $e) {
                    throw new PhiberException(Internationalization::translate("database_connection_error"));
                }

            }
            return $instancia;
        } catch (PhiberException $e) {
            throw new PhiberException(Internationalization::translate("database_connection_error"));
        }
    }
}