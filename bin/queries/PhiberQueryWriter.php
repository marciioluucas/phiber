<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\bin\queries;

use phiber\bin\exceptions\PhiberException;
use phiber\bin\interfaces\IPhiberQueryBuilder;
use ReflectionMethod;
use phiber\util\Internationalization;


/**
 * Classe responsável por escrever os SQLs
 * @package bin
 */
class PhiberQueryWriter implements IPhiberQueryBuilder
{

    /**
     * @var
     */
    private $sql;

    /**
     * PhiberQueryWriter constructor.
     * @param $method
     * @param $infos
     */
    public function __construct($method, $infos)
    {

        $reflectionMethod = new ReflectionMethod(get_class($this), $method);
        $reflectionMethod->invoke($this, $infos);
    }

    /**
     * @return mixed
     */
    function __toString()
    {
        return $this->sql;
    }


    /**
     * @param $infos
     * @return mixed
     * @throws PhiberException
     * @internal param $object
     */
    public function create($infos)
    {
        $tabela = $infos['table'];
        $campos = $infos['fields'];
        $camposV = $infos['values'];
        try {
            $camposNome = [];

            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $camposNome[$i] = $campos[$i];
                }
            }


            $camposNome = array_values($camposNome);
            $this->sql = "INSERT INTO $tabela (" . implode(", ", $camposNome) . ") VALUES (";

            for ($j = 0; $j < count($camposNome); $j++) {
                if ($j != count($camposNome) - 1) {
                    $this->sql .= ":" . $camposNome[$j] . ", ";
                } else if ($j == count($camposNome) - 1) {
                    $this->sql .= ":" . $camposNome[$j] . ")";
                }
            }
            return $this->sql;

        } catch (PhiberException $e) {
            throw new PhiberException(new Internationalization("query_processor_error"));
        }
    }

    /**
     * Faz a query de update de um registro no banco com os dados.
     * @param $infos
     * @return mixed
     * @throws PhiberException
     * @internal param $object
     * @internal param $id
     */
    public function update($infos)
    {

        $tabela = $infos['table'];
//        $conditions = $infos['conditions'];
//        $conjunctions = $infos['conjunctions'];
        $campos = $infos['fields'];
        $camposV = $infos['values'];
        $whereCriteria = $infos['where'];

        try {
            $camposNome = [];
            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $camposNome[$i] = $campos[$i];
                }
            }
//
//            $nomeCampos = [];
            $camposNome = array_values($camposNome);
            $this->sql = "UPDATE $tabela SET ";
//
            for ($i = 0; $i < count($camposNome); $i++) {
                if (!empty($camposNome[$i])) {
                    if ($i != count($camposNome) - 1) {
                        $this->sql .= $camposNome[$i] . " = :" . $camposNome[$i] . ", ";
                    } else {
                        $this->sql .= $camposNome[$i] . " = :" . $camposNome[$i];
                    }
                }
            }
//
//            if ($conditions != null && $whereCriteria == null) {
//                $condWithIntIndex = array_keys($conditions);
//                for ($i = 0; $i < count($conditions); $i++) {
//                    $nomeCampos[$i] = $condWithIntIndex[$i];
//                }
//                $valoresCampos = [];
//                for ($j = 0; $j < count($conditions); $j++) {
//                    $valoresCampos[$j] = $conditions[$nomeCampos[$j]];
//                }
//
//                $this->sql .= " WHERE ";
//
//                for ($x = 0; $x < count($nomeCampos); $x++) {
//                    if ($x != count($nomeCampos) - 1) {
//                        $this->sql .= $nomeCampos[$x] . " = :condition_$nomeCampos[$x] $conjunctions[$x] ";
//                    } else if ($x == count($nomeCampos) - 1) {
//                        $this->sql .= $nomeCampos[$x] . " = :condition_$nomeCampos[$x]";
//                    }
//                }
//            } else if ($conditions == null && $whereCriteria != null) {
            if (!empty($whereCriteria)) {
                $this->sql .= " WHERE " . $whereCriteria . " ";
            }
//            }
            return $this->sql . ";";
        } catch (PhiberException $e) {
            throw new PhiberException(new Internationalization("query_processor_error"));
        }

    }


    /**
     *  Faz a query de delete de um registro no banco com os dados.
     * @param $infos
     * @return bool|string
     * @throws PhiberException
     * @internal param $object
     * @internal param array $conditions
     * @internal param array $conjunctions
     */
    public function delete($infos)
    {
        $tabela = $infos['table'];
        $whereCriteria = $infos['where'];


        try {
            $this->sql = "DELETE FROM $tabela ";
            if (!empty($whereCriteria)) {
                $this->sql .= " WHERE " . $whereCriteria . " ";
            }
            return $this->sql . ";";
        } catch (PhiberException $e) {
            throw new PhiberException(new Internationalization("query_processor_error"));
        }
    }


    /**
     * Faz a query de select de um registro no banco com os dados.
     * @param $infos
     * @return string
     * @throws PhiberException
     */
    public function select($infos)
    {
        try {

            $tabela = $infos['table'];
            $campos = isset($infos['fields']) ? $infos['fields'] : ["*"];

            $whereCriteria = $infos['where'];
            $joins = $infos['join'];
            $limitCriteria = $infos['limit'];
            $offsetCriteria = $infos['offset'];
            $orderByCriteria = $infos['orderby'];


            $campos = gettype($campos) == "array" ? implode(", ", $campos) : $campos;
            $this->sql = "SELECT " . $campos . " FROM $tabela ";
            if (!empty($joins)) {
                $this->sql .= " " . $joins . " ";
            }
            if (!empty($whereCriteria)) {
                $this->sql .= " WHERE " . $whereCriteria . " ";
            }

            if (!is_null($orderByCriteria)) {
                $orderBy = gettype($orderByCriteria) == "array" ? implode(", ", $orderByCriteria) : $orderByCriteria;

                $this->sql .= " ORDER BY " . $orderBy . " ";
            }

            if (!empty($limitCriteria)) {
                $this->sql .= " LIMIT " . $limitCriteria . " ";
            }

            if (!empty($offsetCriteria)) {

                $this->sql .= " OFFSET " . $offsetCriteria . " ";
            }

            return $this->sql . ";";

        } catch (PhiberException $phiberException) {
            throw new PhiberException(new Internationalization("query_processor_error"));
        }

    }
}
