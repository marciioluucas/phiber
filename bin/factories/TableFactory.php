<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace bin\factories;

use bin\Link;

/**
 * Classe responsável por ser a fábrica de tabelas
 * @package bin
 */
abstract class TableFactory
{
    /**
     * Dá o create na tabela
     * @param $obj
     * @return mixed
     */
    abstract static function create($obj);

    /**
     * Dá o alter na tabela
     * @param $obj
     * @return mixed
     */
    abstract static function alter($obj);

    /**
     * Exclui a tabela
     * @param $obj
     * @return mixed
     */
    abstract static function drop($obj);


    /**
     * Sincroniza a tabela com o código
     * @param $obj
     * @return mixed
     */
    abstract static function sync($obj);

    /**
     * Pega a conexão com o banco
     * @return \PDO
     */
    public function getConnection(){
        $pdo = new Link();
        return $pdo->getConnection();
    }
}