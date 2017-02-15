<?php
require_once '../util/JsonReader.php';

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
     * Construtor da classe Banco .
     */
    function __construct()
    {
    }

    public static function getConnection()
    {
        try {
            if (!isset(self::$instancia)) {
                $json = new JsonReader("../phiber_config.json");
                self::$instancia = new PDO(
                    $json->read()->phiber->link->url,
                    $json->read()->phiber->link->usuario,
                    $json->read()->phiber->link->senha,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            }
            return self::$instancia;
        } catch (Exception $e) {
            throw new Exception("Erro ao conectar no banco", 0, $e);
        }
    }
}