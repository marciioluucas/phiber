<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace bin\persistence;


use bin\Config;
use bin\factories\PhiberPersistenceFactory;
use bin\queries\PhiberQueryWriter;
use bin\queries\Restrictions;
use PDO;
use util\FuncoesReflections;
use util\JsonReader;


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
     * @var string
     */
    private $sql = "";


    /**
     * @var Restrictions
     */
    private $restrictions;

    /**
     * @return Restrictions
     */
    public function restrictions(): Restrictions
    {
        return $this->restrictions;
    }

    /**
     * PhiberPersistence constructor.
     * @param $obj
     */
    public function __construct($obj)
    {
        $this->restrictions = new Restrictions();
        $funcoesReflections = new FuncoesReflections();
//        TableMysql::sync($obj);
        $this->phiberConfig = new Config();
        $this->table = strtolower($funcoesReflections->pegaNomeClasseObjeto($obj));
        $this->fields = $funcoesReflections->pegaAtributosDoObjeto($obj);
        $this->fieldsValues = $funcoesReflections->pegaValoresAtributoDoObjeto($obj);
    }


    /**
     * Faz a criação do objeto especificado no banco de dados, caso a opção
     * execute_queries na configuração esteja habilitada.
     * @param <T> $obj
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

            for ($i = 1; $i < count($this->fields); $i++) {
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

//TODO: FAZER OS METODOS DE CREATE QUERY PEGAR COMO PARAMETRO AS REFLECTIONS;

    /**
     * Faz o update no banco do objeto especificado, se caso a opção execute_queries estiver habilitada
     * @param <T> $obj
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
            for ($i = 1; $i < count($this->fields); $i++) {
                if ($this->fieldsValues[$i] != null) {
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
        return $this->sql;
    }

    /**
     * Faz o delete no banco do objeto especificado, se caso a opção execute_queries estiver habilitada
     * @param <T> $obj
     * @param null $infos
     * @return array|bool|mixed|string
     */
    public function delete()
    {
//        if ($infos != null) {
//            $this->sql = new PhiberQueryWriter("select", [
//                "table" => $this->table,
//                "conditions" => isset($infos['conditions']) ? $infos['conditions'] : null,
//                "conjunctions" => isset($infos['conjunctions']) ? $infos['conjunctions'] : null
//            ]);
//        } else if ($infos == null) {

        $this->sql = new PhiberQueryWriter("delete", [
            "table" => $this->table,
            "where" => $this->infosMergeds['where'],

        ]);
//        }

        if ($this->phiberConfig->verifyExecuteQueries()) {
            $pdo = $this->getConnection()->prepare($this->sql);
//            if ($infos != null) {
//                for ($i = 0; $i < count($infos['conditions']); $i++) {
//                    $pdo->bindValue(
//                        "condition_" . $infos['conditions'][$i][0],
//                        $infos['conditions'][$i][2]
//                    );
//                }
//            } else if ($infos == null) {
            if (isset($this->infosMergeds['fields_and_values'])) {
                for ($i = 0; $i < count($this->infosMergeds['fields_and_values']); $i++) {
                    $pdo->bindValue(
                        "condition_" . key($this->infosMergeds['fields_and_values']),
                        $this->infosMergeds['fields_and_values'][key($this->infosMergeds['fields_and_values'])]
                    );
                }
            }
//            }
            if ($pdo->execute()) {
                return true;
            }
        }
        return $this->sql;
    }

    /**
     * Faz o rowCount (contagem de linhas) objeto especificado, se caso a opção execute_queries estiver habilitada
     * @param null $infos
     * @return mixed|int
     * @internal param Object $obj
     * @internal param array $condicoes
     * @internal param array $conjuncoes
     */
    public function rowCount()
    {
        return count($this->select($infos));
    }

    /**
     * Faz a seleção no banco do objeto especificado, se caso a opção execute_queries estiver habilitada
     * @param null $infos
     * @return array|bool|mixed
     */
    public function select()
    {
//        if ($infos != null) {
//            $this->sql = new PhiberQueryWriter("select", [
//                "table" => $this->table,
//                "fields" => isset($infos['fields']) ? $infos['fields'] : "*",
//                "conditions" => isset($infos['conditions']) ? $infos['conditions'] : null,
//                "conjunctions" => isset($infos['conjunctions']) ? $infos['conjunctions'] : null
//            ]);
//        } else if ($infos == null) {
        $fields = isset($this->infosMergeds['fields']) ?
            implode(", ", $this->infosMergeds['fields']) :
            "*";

        $this->sql = new PhiberQueryWriter("select", [
            "table" => $this->table,
            "fields" => $fields,
            "where" => isset($this->infosMergeds['where']) ?
                $this->infosMergeds['where'] :
                null,

        ]);
//        }

        $result = [];
        if ($this->phiberConfig->verifyExecuteQueries()) {
            $pdo = $this->getConnection()->prepare($this->sql);
//            if ($infos != null) {
//                for ($i = 0; $i < count($infos['conditions']); $i++) {
//                    $pdo->bindValue(
//                        "condition_" . $infos['conditions'][$i][0],
//                        $infos['conditions'][$i][2]
//                    );
//                }
//            } else if ($infos == null) {
            if (isset($this->infosMergeds['fields_and_values'])) {

                while (current($this->infosMergeds['fields_and_values'])) {
                    $pdo->bindValue(
                        "condition_" . key($this->infosMergeds['fields_and_values']),
                        $this->infosMergeds['fields_and_values'][key($this->infosMergeds['fields_and_values'])]);
                    next($this->infosMergeds['fields_and_values']);
                }


            }
//            }
            $pdo->execute();
            $result = $pdo->fetchAll((PDO::FETCH_ASSOC));

        }
        return $result;
    }

//    /**
//     * Caso queira criar uma query.
//     * @param String $query
//     * @return mixed|void
//     */
//    public function createQuery($query)
//    {
//        // TODO: Implement createQuery() method.
//    }


    /**
     * Adiciona parâmetros da classe restriction nas informações para buildar o SQL.
     * @param $infos
     */
    public function add($infos)
    {
        array_push($this->infos, $infos);
        if(!isset($this->infos['fields'])){
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