<?php
namespace bin;

/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 16/03/17
 * Time: 16:31
 */
interface IPhiberPersistence
{


    public function create($obj);

    public function update($obj, $id);

    public function delete($obj, $condicoes = [], $conjuncoes = []);

    public function rowCount($obj, $condicoes = [], $conjuncoes = []);

    public function select($obj, $infos);

    public function createQuery($query);
}