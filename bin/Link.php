<?php
/**
 * Classe criada por Márcio Lucas R de Oliveira (lukee)
 * E-mail: marciioluucas@gmail.com
 * Date: 19/10/2016
 * Time: 18:50
 */
namespace bin;

use bin\exceptions\PhiberException;
use PDO;
use util\Internationalization;
use util\JsonReader;


/**
 * Classe responsável por criar a conexão do banco.
 * @package bin
 */
class Link
{

    /**
     * Função responsável por fazer a conexão com o banco.
     * @return PDO
     * @throws PhiberException
     */
    public static function getConnection()
    {
        try {
            if (!isset($instancia)) {
                $json = JsonReader::read(BASE_DIR . "/phiber_config.json");
                try {
                   $instancia = new PDO(
                        $json->phiber->link->url,
                        $json->phiber->link->user,
                        $json->phiber->link->password,
                        array(PDO::ATTR_PERSISTENT => $json->phiber->link->connection_cache == 1 ? true : false));
                }
                catch (PhiberException $e) {
                    throw new PhiberException(new Internationalization("database_connection_error"));
                }

            }
            return $instancia;
        } catch (PhiberException $e) {
            throw new PhiberException(new Internationalization("database_connection_error"));
        }
    }
}