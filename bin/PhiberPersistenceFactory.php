<?php
namespace phiber\bin;


/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 16:31
 */
abstract class PhiberPersistenceFactory
{


    /**
     * @return null|\PDO
     */
    public function getConnection(){
        return Link::getConnection();
    }

    public abstract  function execute($sql);
    public abstract  function create($obj);
    public abstract  function update($obj, $id);
    public abstract  function delete($obj, $condicoes = [], $conjuncoes = []);
    public abstract  function rowCount($obj, $condicoes = [], $conjuncoes = []);
    public abstract  function search($obj, $condicoes = null, $retornaPrimeiroValor = false);
    public abstract  function createQuery($query);
}