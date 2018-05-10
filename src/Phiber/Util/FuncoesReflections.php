<?php

/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */
namespace Phiber\Util;

use Exception;
use Phiber\ORM\Exceptions\NotImplementedException;
use ReflectionClass;
use ReflectionProperty;

/**
 *
 * Classe responsável por Fazer a reflexao das classes dos objetos.
 * @package util
 */
class FuncoesReflections
{
    /**
     * Função responsável por pegar o nome dos métodos do objeto retornando um array dos mesmos
     * 
     * @param  $object
     * @return array
     */
    public function pegaNomesMetodosClasse($object)
    {
        return get_class_methods($object);
    }

    /**
     * Função responsável por pegar o nome de um atributo espefífico.
     * Caso o atributo pesquisado não exista, a função retornará falso.
     * 
     * @param  $object
     * @param  $nomeAtributo
     * @return bool|string
     * @throws Exception
     */
    public function pegaNomeAtributoEspecifico($object, $nomeAtributo)
    {
        try {
            $arrayAtributosObjeto = self::pegaAtributosDoObjeto($object);
            
            $iterator = 0;
            $limit    = count($arrayAtributosObjeto);
            for ($iterator; $iterator < $limit; $iterator++) {
                
                $atributoEspecifico = strstr(
                    $arrayAtributosObjeto[$iterator], 
                    $nomeAtributo
                );
                
                return $atributoEspecifico;
            }

        } catch (Exception $e) {
            throw new Exception("Falha ao pegar nome do atributo específico", 3, $e);
        }

        return false;
    }

    /**
     * Função responsável por pegar os nomes dos atributos do objeto, retornando um array dos mesmos.
     * 
     * @param $obj
     * @return array
     */
    public function pegaAtributosDoObjeto($obj)
    {
        $properties      = [];
        $reflectionClass = new ReflectionClass($obj);
        $propriedades    = $reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED | 
            ReflectionProperty::IS_PRIVATE
        );
     
        $iterator = 0;
        $limit    = count($propriedades);
        for ($iterator = 0; $iterator < $limit; $iterator++) {
            $properties[$iterator] = $propriedades[$iterator]->name;
        }

        return $properties;
    }

    /**
     * Função responsável por pegar o nome da classe do objeto em questão.
     * 
     * @param  $object
     * @return string
     */
    public function pegaNomeClasseObjeto($object)
    {
        $reflectionClass = new ReflectionClass($object);

        return $reflectionClass->getShortName();
    }

    /**
     * Função responsável por retornar os valores dos atributos das classes mães,
     * se as mesmas existirem, se caso a classe em questão não for uma classe filha, 
     * a função retornará false.
     * 
     * @param  $object
     * @return bool|array
     */
    public function retornaValoresAtributosClassesMaes($object)
    {
        if (self::verificaSeEClasseFilha($object)) {
            
            $nomeClassesMae = self::retornaClassesMaes($object);
            
            $valores  = [];
            $iterator = 0;
            $limit    = count($nomeClassesMae);
            for ($iterator; $iterator < $limit; $iterator++) {
                $valores[$iterator] = self::pegaValoresAtributoDoObjeto(
                    $nomeClassesMae[$iterator]
                );
            }

            return $valores;
        }

        return false;
    }

    /**
     * Função responsável por verifidar se a classe é filha de alguma outra classe,
     * se caso não for. A função retornará falso.
     * 
     * @param  Object $object
     * @return bool
     */
    public function verificaSeEClasseFilha($object)
    {
        $class = new ReflectionClass($object);

        if ($class->getParentClass()) {
            return true;
        }
        
        return false;
    }

    /**
     * Retorna o nome das classes mães, se caso o objeto em questão não ter uma classe mãe,
     * a função retornará false.
     * 
     * @param  $object
     * @return array|bool
     */
    public function retornaClassesMaes($object)
    {
        $class = new ReflectionClass($object);

        $parents = [];
        $parent  = "";
        if ( self::verificaSeEClasseFilha($object) ) {
            while ($class->getParentClass()) {
                $parents[] = $class->getParentClass()->getName();
                $class     = $parent;
            }

            return $parents;
        }

        return false;
    }

    /**
     * Função responsável por retornar um array de todos os valores dos atributos de um objeto
     * 
     * @param $object
     * @return array
     */
    public function pegaValoresAtributoDoObjeto($object)
    {
        $nomeAtributos   = self::pegaAtributosDoObjeto($object);
        $valAtrFinal     = [];
        $reflectionClass = new ReflectionClass($object);
        
        $iterator = 0;
        $limit    = count($nomeAtributos);
        for ($iterator; $iterator < $limit; $iterator++) {
            $reflectionProperty = $reflectionClass->getProperty($nomeAtributos[$iterator]);
            $reflectionProperty->setAccessible(true);
            $valAtrFinal[$iterator] = $reflectionProperty->getValue($object);
        }

        return $valAtrFinal;
    }


    /**
     * Função responsável por retornar um array com todos os atributos das classes da hierarquia
     * 
     * @todos Pendente de implementação
     * 
     * @param $obj
     * @return array
     */
    public function retornaNomeAtributosClassesMaes($obj)
    {
        throw new NotImplementedException();

        $atributos = [];
        $iterator  = 0;
        $limit     = count(self::retornaClassesMaes($obj));
        for ($iterator; $iterator < $limit; $iterator++) {
            $atributos[$iterator] = array(self::retornaClassesMaes($obj)[$iterator] =>
                self::pegaAtributosDoObjeto(self::retornaClassesMaes($obj)[$iterator]));
        }

        return $atributos;
    }

    /**
     * Função responsável por retornar os comentários dos atributos do objeto em questão.
     * 
     * @param  $object
     * @return array
     */
    public function retornaComentariosAtributos($object)
    {
        $arrAttrNames = self::pegaAtributosDoObjeto($object);
        $arrAttrComm  = array();
        
        $iterator = 0;
        $limit    = count($arrAttrNames);
        for ($iterator; $iterator < $limit; $iterator++) {

            $reflectionAttr = new ReflectionProperty($object, $arrAttrNames[$iterator]);

            $arrAttrComm[$arrAttrNames[$iterator]] = $reflectionAttr->getDocComment();
        }

        /**
         * @todo FAZER O FOR PARA PEGAR TODOS ATRIBUTOS, PASSAR NO SEGUNDO PARAMETRO;
         */
        return $arrAttrComm;
    }
}
