<?php
/**
 * Classe criada por Márcio Lucas R de Oliveira (lukee)
 * E-mail: marciioluucas@gmail.com
 * Date: 19/10/2016
 * Time: 18:50
 */
namespace phiber\bin;

use phiber\bin\exceptions\PhiberException;
use PDO;
use phiber\util\Internationalization;
use phiber\util\JsonReader;


/**
 * Classe responsável por criar a conexão do banco.
 * @package bin
 */
class Link
{

    private $instancia;



    /**
     * Função responsável por fazer a conexão com o banco.
     * @throws PhiberException
     */
    public function getConnection()
    {
        try {
            if ($this->instancia == null) {

                $json = new JsonReader(BASE_DIR . "/phiber_config.json");

                if (!empty(glob(dirname(__DIR__, 4) . "/phiber_config.json")[0])) {
                    $json = new JsonReader(glob(dirname(__DIR__, 4) . "/phiber_config.json")[0]);
                }

                $json= $json->read();
                try {
                    $this->instancia = new PDO(
                        $json->phiber->link->url,
                        $json->phiber->link->user,
                        $json->phiber->link->password,
                        array(PDO::ATTR_PERSISTENT => $json->phiber->link->connection_cache == 1 ? true : false));
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