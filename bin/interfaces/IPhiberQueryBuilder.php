<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace bin\interfaces;


/**
 * Interface IPhiberQueryBuilder
 * @package bin
 */
interface IPhiberQueryBuilder
{
    /**
     * Cria a sql do INSERT
     * @param $infos
     * @return mixed
     */
    public function create($infos);

    /**
     * Cria a sql do UPDATE
     * @param $infos
     * @return mixed
     */
    public function update($infos);

    /**
     * Cria a sql do DELETE
     * @param $infos
     * @return mixed
     */
    public function delete($infos);

    /**
     * Cria a sql do SELECT
     * @param $infos
     * @return mixed
     */
    public function select($infos);
}