<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\ORM\Persistence;

use PDO;
use PDOStatement;
use Phiber\ORM\Config;
use Phiber\ORM\factories\PhiberPersistenceFactory;
use Phiber\ORM\Queries\PhiberQueryWriter;
use Phiber\ORM\Queries\Restrictions;
use Phiber\Util\FuncoesReflections;
use Phiber\Util\JsonReader;

/**
 * Classe responsável por persistir os objetos no banco
 * 
 * @package bin
 */
class PhiberPersistence extends PhiberPersistenceFactory
{
    /**
     * Variável da configuração do Phiber
     * 
     * @var JsonReader
     */
    private $phiberConfig;

    /**
     * Variável da tabela do objeto trabalhado
     * 
     * @var string
     */
    private $table = "";

    /**
     * Campos/colunas do objeto trabalhado
     * 
     * @var array
     */
    private $fields = [];
    
    /**
     * Valores/colunas dos campos
     * 
     * @var array
     */
    private $fieldsValues = [];

    /**
     * Informações para a criação da SQL.
     * 
     * @var array
     */
    private $infos = [];

    /**
     * Informações mergidas
     * 
     * @var array
     */
    private $infosMergeds = [];

    /**
     * Informações mergidas
     * 
     * @var array
     */
    private $rowCount = 0;

    /**
     * @var string
     */
    private $sql = "";

    /**
     * Array que vai ter os joins.
     * 
     * @var array
     */
    private $joins = [];

    /**
     * Variável que instancia PDO
     * 
     * @var PDOStatement
     */
    private $PDO = null;

    /**
     * @var Restrictions
     */
    public $restrictions;

    /**
     * @var bool
     */
    private $returnSelectWithArray = false;

    /**
     * @deprecated use restriction sem o parênteses.
     * @return Restrictions
     */
    public function restrictions()
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
     * 
     * @param string $table
     */
    public function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * Seleciona os campos manualmente para a escrita da SQL
     * 
     * @param array $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Seleciona os valores dos campos manualmente para o binding após a escrita da SQL
     * 
     * @param array $fieldsValues
     */
    public function setValues(array $fieldsValues)
    {
        $this->fieldsValues = $fieldsValues;
    }

    /**
     * Faz o rowCount (contagem de linhas) objeto especificado, se caso a opção execute_queries 
     * estiver habilitada
     * 
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
     * 
     * @param $obj
     */
    public function __construct($obj = "")
    {
        $this->restrictions = new Restrictions();
        $funcoesReflections = new FuncoesReflections();
        $this->phiberConfig = new Config();
        $this->PDO          = $this->getConnection();

        if ($obj != "") {
            $this->table = strtolower($funcoesReflections->pegaNomeClasseObjeto($obj));
            $this->fields = $funcoesReflections->pegaAtributosDoObjeto($obj);

            $this->fieldsValues = $funcoesReflections->pegaValoresAtributoDoObjeto($obj);
        }
    }

    /**
     * Faz a criação do objeto especificado no banco de dados, caso a opção
     * execute_queries na configuração esteja habilitada.
     * 
     * @return bool|mixed
     */
    public function create()
    {
        $this->sql = new PhiberQueryWriter("create", [
            "table"  => $this->table,
            "fields" => $this->fields,
            "values" => $this->fieldsValues
        ]);

        if ($this->phiberConfig->verifyExecuteQueries()) {
            $stmt = $this->PDO->prepare($this->sql);

            for ($i = 0; $i < count($this->fields); $i++) {
                if ($this->fieldsValues[$i] != null) {
                    $stmt->bindValue($this->fields[$i], $this->fieldsValues[$i]);
                }
            }
            if ($stmt->execute()) {
                return $this->PDO->lastInsertId();
            }
        }

        return false;
    }

    /**
     * Faz o update no banco do objeto especificado, se caso a opção execute_queries 
     * estiver habilitada
     * 
     * @return mixed
     * @internal param array $conditions
     * @internal param array $conjunctions
     */
    public function update()
    {
        $conditions = $this->infosMergeds['fields_and_values'];

        $this->sql = new PhiberQueryWriter("update", [
            "table"  => $this->table,
            "fields" => $this->fields,
            "values" => $this->fieldsValues,
            "where"  => isset($this->infosMergeds['where']) ?
                $this->infosMergeds['where'] :
                null,
            "limit"  => isset($this->infosMergeds['limit']) ?
                $this->infosMergeds['limit'] :
                null,
            "offset"  => isset($this->infosMergeds['offset']) ?
                $this->infosMergeds['offset'] :
                null
        ]);

        if ($this->phiberConfig->verifyExecuteQueries()) {

            $this->PDO = $this->PDO->prepare($this->sql);
            for ($i = 0; $i < count($this->fields); $i++) {
                if (!empty($this->fieldsValues[$i])) {
                    $this->PDO->bindValue($this->fields[$i], $this->fieldsValues[$i]);
                }
            }

            while (current($conditions)) {
                $this->PDO->bindValue(
                    "condition_" . key($conditions), 
                    $conditions[key($conditions)]
                );
                
                next($conditions);
            }

            if ($this->PDO->execute()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Faz o delete no banco do objeto especificado, se caso a opção execute_queries 
     * estiver habilitada
     * 
     * @return   mixed
     * @internal param $ <T> $obj
     * @internal param null $infos
     */
    public function delete()
    {
        $this->sql = new PhiberQueryWriter("delete", [
            "table"  => $this->table,
            "where"  => isset($this->infosMergeds['where']) ?
                $this->infosMergeds['where'] :
                null,
            "limit"  => isset($this->infosMergeds['limit']) ?
                $this->infosMergeds['limit'] :
                null,
            "offset" => isset($this->infosMergeds['offset']) ?
                $this->infosMergeds['offset'] :
                null
        ]);

        if ($this->phiberConfig->verifyExecuteQueries()) {

            $this->PDO = $this->PDO->prepare($this->sql);
            
            if (isset($this->infosMergeds['fields_and_values'])) {

                while (current($this->infosMergeds['fields_and_values'])) {
                    $this->PDO->bindValue(
                        "condition_" . key($this->infosMergeds['fields_and_values']),
                        $this->infosMergeds['fields_and_values'][key($this->infosMergeds['fields_and_values'])]
                    );

                    next($this->infosMergeds['fields_and_values']);
                }
            }
            if ($this->PDO->execute()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Faz a seleção no banco do objeto especificado, se caso a opção execute_queries 
     * estiver habilitada
     * 
     * @return array
     * @internal param null $infos
     */
    public function select()
    {
        $fields = !empty($this->fields) ? $this->fields : ["*"];
        if (empty($this->fields)) {
            $fields = isset($this->infosMergeds['fields']) ?
                implode(", ", $this->infosMergeds['fields']) :
                "*";
        }

        $this->sql = new PhiberQueryWriter("select", [
            "table" => $this->table,
            "fields" => $fields,
            "where" => isset($this->infosMergeds['where']) ?
                $this->infosMergeds['where'] :
                null,
            "limit" => isset($this->infosMergeds['limit']) ?
                $this->infosMergeds['limit'] :
                null,
            "offset" => isset($this->infosMergeds['offset']) ?
                $this->infosMergeds['offset'] :
                null,
            "orderby" => isset($this->infosMergeds['orderby']) ?
                $this->infosMergeds['orderby'] :
                null,
            "join" => isset($this->joins) ?
                $this->joins :
                null
        ]);

        $result = [];
        if ($this->phiberConfig->verifyExecuteQueries()) {
            $this->PDO = $this->PDO->prepare($this->sql);
            if (isset($this->infosMergeds['fields_and_values'])) {

                while (current($this->infosMergeds['fields_and_values'])) {
                    $this->PDO->bindValue(
                        "condition_" . key($this->infosMergeds['fields_and_values']),
                        $this->infosMergeds['fields_and_values'][key($this->infosMergeds['fields_and_values'])]);
                    next($this->infosMergeds['fields_and_values']);
                }
            }
            $this->PDO->execute();

            $result = $this->PDO->fetchAll((PDO::FETCH_ASSOC));

            $this->rowCount = $this->PDO->rowCount();
        }

        return $result;
    }

    /**
     * Adiciona parâmetros da classe restriction nas informações para buildar o SQL.
     * 
     * @param $infos
     */
    public function add($infos)
    {
        array_push($this->infos, $infos);
        
        if (!isset($this->infos['fields'])) {
            $this->infos['fields'] = ["*"];
        }

        if (isset($infos['join'])) {
            array_push($this->joins, $infos['join']);
        }
        
        $this->mergeSqlInformation();
    }

    /**
     * Função responsável por mostrar o string da SQL gerada a partir do objeto
     * 
     * @return string
     */
    public function show()
    {
        return $this->sql;
    }

    /**
     * Função para escrever SQL manualmente
     * 
     * @param string $sql
     */
    public function writeSQL($sql)
    {
        $this->sql = $sql;
        $this->PDO = $this->PDO->prepare($this->sql);
    }

    /**
     * @param $parameter
     * @param $value
     * @param int $data_type
     */
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        $this->PDO->bindValue($parameter, $value, $data_type);
    }

    /**
     * Responsável por executar a query.
     *
     * @return void
     */
    public function execute()
    {
        $this->PDO->execute();
        $this->rowCount = $this->PDO->rowCount();
    }

    /**
     * Responsável por retornar todos os dados da tabela.
     * 
     * @param  int $fetch_style
     * @return array
     */
    public function fetchAll($fetch_style = PDO::FETCH_ASSOC)
    {
        return $this->PDO->fetchAll($fetch_style);
    }

    /**
     * Responsável por retornar um range de dados desejados.
     * 
     * @param  null $fetch_style
     * @param  int $cursor_orientation
     * @param  int $cursor_offset
     * @return mixed
     */
    public function fetch($fetch_style = PDO::FETCH_ASSOC, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        return $this->PDO->fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }

    /**
     * Função utilizada para mergir informações novas com as antigas da Restrictions
     * 
     * @return void
     */
    private function mergeSqlInformation()
    {
        array_push($this->infos, $this->restrictions->getFieldsAndValues());
        
        $iterator = 0;
        $limit    = count($this->infos) - 1;
        for ($iterator; $iterator < $limit; $iterator++) {
            if (isset(array_keys($this->infos[$iterator])[0])) {
                $this->infosMergeds[array_keys($this->infos[$iterator])[0]] =
                    $this->infos[$iterator][array_keys($this->infos[$iterator])[0]];
            }
        }
    }
}
