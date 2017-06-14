<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\bin\persistence;


use PDO;
use phiber\bin\Config;
use phiber\bin\factories\PhiberPersistenceFactory;
use phiber\bin\queries\PhiberQueryWriter;
use phiber\bin\queries\Restrictions;
use phiber\util\FuncoesReflections;
use phiber\util\JsonReader;


/**
 * Classe responsável por persistir os objetos no banco
 * @package bin
 */
class PhiberPersistence extends PhiberPersistenceFactory
{

    /**
     * Variável da configuração do Phiber
     * @var JsonReader
     */
    private $phiberConfig;
    /**
     * Variável da tabela do objeto trabalhado
     * @var string
     */
    private $table;
    /**
     * Campos/colunas do objeto trabalhado
     * @var array
     */
    private $fields;
    /**
     * Valores/colunas dos campos
     * @var array
     */
    private $fieldsValues;


    /**
     * Informações para a criação da SQL.
     * @var array
     */
    private $infos = [];

    /**
     * Informações mergidas
     * @var array
     */
    private $infosMergeds = [];

    /**
     * Informações mergidas
     * @var array
     */
    private $rowCount = 0;


    /**
     * @var string
     */
    private $sql = "";


    /**
     * @var Restrictions
     */
    private $restrictions;

    private $returnSelectWithArray = false;

    /**
     * @return Restrictions
     */
    public function restrictions(): Restrictions
    {
        return $this->restrictions;
    }

    /**
     * @param bool $isArray
     */
    public function returnArray(bool $isArray = false)
    {
        $this->returnSelectWithArray = $isArray;
    }

    /**
     * Seleciona a tabela manualmente para a escrita da SQL
     * @param string $table
     */
    public function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * Seleciona os campos manualmente para a escrita da SQL
     * @param array $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Seleciona os valores dos campos manualmente para o binding após a escrita da SQL
     * @param array $fieldsValues
     */
    public function setValues(array $fieldsValues)
    {
        $this->fieldsValues = $fieldsValues;
    }



    /**
     * Faz o rowCount (contagem de linhas) objeto especificado, se caso a opção execute_queries estiver habilitada
     * @return int|mixed
     * @internal param null $infos
     * @internal param Object $obj
     * @internal param array $condicoes
     * @internal param array $conjuncoes
     */
    public function rowCount()
    {
        return $this->rowCount;
    }

    /**
     * PhiberPersistence constructor.
     * @param $obj
     */
    public function __construct($obj)
    {
        $this->restrictions = new Restrictions();
        $funcoesReflections = new FuncoesReflections();
        $this->phiberConfig = new Config();
        $this->table = strtolower($funcoesReflections->pegaNomeClasseObjeto($obj));
        $this->fields = $funcoesReflections->pegaAtributosDoObjeto($obj);
        $this->fieldsValues = $funcoesReflections->pegaValoresAtributoDoObjeto($obj);
    }


    /**
     * Faz a criação do objeto especificado no banco de dados, caso a opção
     * execute_queries na configuração esteja habilitada.
     * @return bool|mixed
     */
    public function create()
    {


        $this->sql = new PhiberQueryWriter("create", [
            "table" => $this->table,
            "fields" => $this->fields,
            "values" => $this->fieldsValues
        ]);

        if ($this->phiberConfig->verifyExecuteQueries()) {

            $pdo = $this->getConnection()->prepare($this->sql);

            for ($i = 0; $i < count($this->fields); $i++) {
                if ($this->fieldsValues[$i] != null) {
                    $pdo->bindValue($this->fields[$i], $this->fieldsValues[$i]);
                }
            }
            if ($pdo->execute()) {
                return true;
            }
        }
        return false;

    }


    /**
     * Faz o update no banco do objeto especificado, se caso a opção execute_queries estiver habilitada
     * @return mixed
     * @internal param array $conditions
     * @internal param array $conjunctions
     */
    public function update()
    {

        $conditions = $this->infosMergeds['fields_and_values'];

        $this->sql = new PhiberQueryWriter("update", [
            "table" => $this->table,
            "fields" => $this->fields,
            "values" => $this->fieldsValues,
            "where" => $this->infosMergeds['where'],

        ]);
        if ($this->phiberConfig->verifyExecuteQueries()) {
            $pdo = $this->getConnection()->prepare($this->sql);
            for ($i = 0; $i < count($this->fields); $i++) {
                if (!empty($this->fieldsValues[$i])) {
                    $pdo->bindValue($this->fields[$i], $this->fieldsValues[$i]);
                }

            }

            while (current($conditions)) {
                $pdo->bindValue("condition_" . key($conditions), $conditions[key($conditions)]);
                next($conditions);
            }

            if ($pdo->execute()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Faz o delete no banco do objeto especificado, se caso a opção execute_queries estiver habilitada
     * @return array|bool|mixed|string
     * @internal param $ <T> $obj
     * @internal param null $infos
     */
    public function delete()
    {
        $this->sql = new PhiberQueryWriter("delete", [
            "table" => $this->table,
            "where" => $this->infosMergeds['where'],
        ]);

        if ($this->phiberConfig->verifyExecuteQueries()) {
            $pdo = $this->getConnection()->prepare($this->sql);

            if (isset($this->infosMergeds['fields_and_values'])) {
                for ($i = 0; $i < count($this->infosMergeds['fields_and_values']); $i++) {
                    $pdo->bindValue(
                        "condition_" . key($this->infosMergeds['fields_and_values']),
                        $this->infosMergeds['fields_and_values'][key($this->infosMergeds['fields_and_values'])]
                    );
                }
            }
            if ($pdo->execute()) {
                return true;
            }
        }
        return false;
    }


    /**
     * Faz a seleção no banco do objeto especificado, se caso a opção execute_queries estiver habilitada
     * @return array|bool|mixed
     * @internal param null $infos
     */
    public function select()
    {

        $fields = isset($this->infosMergeds['fields']) ?
            implode(", ", $this->infosMergeds['fields']) :
            "*";

        $this->sql = new PhiberQueryWriter("select", [
            "table" => $this->table,
            "fields" => $fields,
            "where" => isset($this->infosMergeds['where']) ?
                $this->infosMergeds['where'] :
                null

        ]);

        $result = [];
        if ($this->phiberConfig->verifyExecuteQueries()) {
            $pdo = $this->getConnection()->prepare($this->sql);
            if (isset($this->infosMergeds['fields_and_values'])) {

                while (current($this->infosMergeds['fields_and_values'])) {
                    $pdo->bindValue(
                        "condition_" . key($this->infosMergeds['fields_and_values']),
                        $this->infosMergeds['fields_and_values'][key($this->infosMergeds['fields_and_values'])]);
                    next($this->infosMergeds['fields_and_values']);
                }
            }
            $pdo->execute();

            if ($this->returnSelectWithArray and $pdo->rowCount() > 1) {
                $result = $pdo->fetchAll((PDO::FETCH_ASSOC));
            } else {
                if ($pdo->rowCount() != 0) {
                    $result = $pdo->fetch(PDO::FETCH_ASSOC);
                }
            }
            $this->rowCount = $pdo->rowCount();
        }
        return $result;
    }


    /**
     * Adiciona parâmetros da classe restriction nas informações para buildar o SQL.
     * @param $infos
     */
    public function add($infos)
    {
        array_push($this->infos, $infos);
        if (!isset($this->infos['fields'])) {
            $this->infos['fields'] = ["*"];
        }
        $this->mergeSqlInformation();

    }

    /**
     * Função responsável por mostrar o string da SQL gerada a partir do objeto
     * @return string
     */
    public function show()
    {
        return $this->sql;
    }


    /**
     *Função utilizada para mergir informações novas com as antigas da Restrictions
     */
    private function mergeSqlInformation()
    {
        array_push($this->infos, $this->restrictions->getFieldsAndValues());
        for ($i = 0; $i < count($this->infos) - 1; $i++) {
            $this->infosMergeds[array_keys($this->infos[$i])[0]] =
                $this->infos[$i][array_keys($this->infos[$i])[0]];
        }
    }


}