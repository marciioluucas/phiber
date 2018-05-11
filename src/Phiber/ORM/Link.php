<?php

/**
 * Classe criada por Márcio Lucas R de Oliveira (lukee)
 * E-mail: marciioluucas@gmail.com
 * Date: 19/10/2016
 * Time: 18:50
 */
namespace Phiber\ORM;

use PDO;
use Phiber\ORM\Exceptions\PhiberException;
use Phiber\Util\Internationalization;
use Phiber\Util\JsonReader;

/**
 * Classe responsável por criar a conexão do banco.
 */
class Link
{
    const PATH_CONFIG_FILE = '/phiber_config.json';

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $instancia;

    /**
     * Função responsável por fazer a conexão com o banco.
     * @throws PhiberException
     */
    public function getConnection()
    {
        try {
            if ($this->instancia == null) {

                $json = new JsonReader(BASE_DIR . self::PATH_CONFIG_FILE);

                if (!empty(glob(dirname(__DIR__, 4) . self::PATH_CONFIG_FILE )[0])) {
                    $json = new JsonReader(glob(dirname(__DIR__, 4) . PATH_CONFIG_FILE)[0]);
                }

                $json= $json->read();
                try {

                    $connectionCache = $json->phiber->link->connection_cache == 1 ? true : false;

                    $this->instancia = new PDO(
                        $json->phiber->link->url,
                        $json->phiber->link->user,
                        $json->phiber->link->password,
                        array(
                            PDO::ATTR_PERSISTENT => $connectionCache
                        )
                    );
                } catch (PhiberException $e) {
                    throw new PhiberException(new Internationalization("database_connection_error"));
                }
            }

            return $this->instancia;

        } catch (PhiberException $e) {
            throw new PhiberException(new Internationalization("database_connection_error"));
        }
    }
}