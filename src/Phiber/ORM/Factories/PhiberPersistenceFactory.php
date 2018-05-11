<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\ORM\Factories;

use PDO;
use Phiber\Link;

/**
 * Interface IPhiberPersistence
 * @package bin
 */
abstract class PhiberPersistenceFactory
{
    /**
     * Pega a conexão com o banco
     * 
     * @return PDO
     */
    public function getConnection()
    {
        $pdo = new Link();
    
        return $pdo->getConnection();
    }

    /**
     * Faz a criação do objeto no banco
     * 
     * @return mixed
     */
    public abstract function create();

    /**
     * Faz a alteração do objeto no banco
     * 
     * @param  $infos
     * @return mixed
     * @internal param $id
     */
    public abstract function update();

    /**
     * Faz a exclusão do objeto no banco
     * 
     * @param $infos
     * @return mixed
     */
    public abstract function delete();

    /**
     * Faz a contagem de quantos objetos está no banco
     * 
     * @param $infos
     * @return mixed
     * @internal param array $condicoes
     * @internal param array $conjuncoes
     */
    public abstract function rowCount();

    /**
     * Faz a seleção dos objetos no banco
     * 
     * @param $infos
     * @return mixed
     */
    public abstract function select();
}
