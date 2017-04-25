<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace util;

use Exception;
use ReflectionClass;
use ReflectionProperty;

/**
 *
 * Classe responsável por Fazer a reflexao das classes dos objetos.
 * @package util
 */
class FuncoesReflections
{


//    /**
//     * Construtor da classe FuncoesReflections
//     */
//    public function __construct($method, $obj)
//    {
//        $reflectionMethod = new ReflectionMethod(get_class($this), $method);
//        $reflectionMethod->invoke($this, $obj);
//    }


//    public function pegaAtributoDoObjeto($obj)
//    {
//        $reflectionClass = new ReflectionClass($obj);
//        $propriedades = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC |
//            ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);
//
//        for ($i = 0; $i < count($propriedades); $i++) {
//            self::$p[$i] = $propriedades[$i]->name;
//        }
//        if ($reflectionClass->getParentClass() != null) {
//            self::pegaAtributoDoObjeto($reflectionClass->getParentClass());
//        }
//        return self::$p;
//    }

    /**
     * Função responsável por pegar o nome dos métodos do objeto retornando um array dos mesmos
     * @param $obj
     * @return array
     */
    public function pegaNomesMetodosClasse($obj)
    {
        return get_class_methods($obj);
    }

    /**
     * Função responsável por pegar o nome de um atributo espefífico.
     * Caso o atributo pesquisado não exista, a função retornará falso.
     * @param $obj
     * @param $nomeAtributo
     * @return bool|string
     * @throws Exception
     */
    public function pegaNomeAtributoEspecifico($obj, $nomeAtributo)
    {
        try {
            $arrayAtributosObjeto = self::pegaAtributosDoObjeto($obj);
            for ($i = 0; $i < count($arrayAtributosObjeto); $i++) {
                $atributoEspecifico = strstr($arrayAtributosObjeto[$i], $nomeAtributo);
                return $atributoEspecifico;
            }
        } catch (Exception $e) {
            throw new Exception("Falha ao pegar nome do atributo específico", 3, $e);
        }
        return false;
    }

    /**
     * Função responsável por pegar os nomes dos atributos do objeto, retornando um array dos mesmos.
     * @param $obj
     * @return array
     */
    public function pegaAtributosDoObjeto($obj)
    {
        $properties = [];
        $reflectionClass = new ReflectionClass($obj);
        $propriedades = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        for ($i = 0; $i < count($propriedades); $i++) {
            $properties[$i] = $propriedades[$i]->name;
        }
//        if ($reflectionClass->getParentClass() != null) {
//            self::pegaAtributoDoObjeto($reflectionClass->getParentClass());
//        }
        return $properties;
    }


    /**
     * Função responsável por pegar o nome da classe do objeto em questão.
     * @param $obj
     * @return string
     */
    public function pegaNomeClasseObjeto($obj)
    {
        $reflectionClass = new ReflectionClass($obj);
        return $reflectionClass->getShortName();
    }


    /**
     * Função responsável por verifidar se a classe é filha de alguma outra classe,
     * se caso não for. A função retornará falso.
     * @param \Object $obj
     * @return bool
     */
    public function verificaSeEClasseFilha($obj)
    {
        $class = new ReflectionClass($obj);
        if ($class->getParentClass()) {
            return true;
        }
        return false;

    }

    /**
     * Função responsável por retornar os valores dos atributos das classes mães,
     * se as mesmas existirem, se caso a classe em questão não for uma classe filha, a função retornará
     * false.
     * @param $obj
     * @return bool|array
     */
    public function retornaValoresAtributosClassesMaes($obj)
    {
        if (self::verificaSeEClasseFilha($obj)) {
            $nomeClassesMae = self::retornaClassesMaes($obj);
            $valores = [];
            for ($i = 0; $i < count($nomeClassesMae); $i++) {
                $valores[$i] = self::pegaValoresAtributoDoObjeto($nomeClassesMae[$i]);
            }
            return $valores;
        }
        return false;
    }

    /**
     * Retorna o nome das classes mães, se caso o objeto em questão não ter uma classe mãe,
     * a função retornará false.
     * @param $obj
     * @return array|bool
     */
    public function retornaClassesMaes($obj)
    {
        $class = new ReflectionClass($obj);

        $parents = [];
        $parent = "";
        if (self::verificaSeEClasseFilha($obj)) {
            while ($class->getParentClass()) {
                $parents[] = $class->getParentClass()->getName();
                $class = $parent;
            }
            return $parents;
        }
        return false;
    }

    /**
     * Função responsável por retornar um array de todos os valores dos atributos de um objeto
     * @param $obj
     * @return array
     */
    public function pegaValoresAtributoDoObjeto($obj)
    {
        $nomeAtributos = self::pegaAtributosDoObjeto($obj);
        $valAtrFinal = [];
        $reflectionClass = new ReflectionClass($obj);
        for ($i = 0; $i < count($nomeAtributos); $i++) {
            $reflectionProperty = $reflectionClass->getProperty($nomeAtributos[$i]);
            $reflectionProperty->setAccessible(true);
            $valAtrFinal[$i] = $reflectionProperty->getValue($obj);
        }
        return $valAtrFinal;
    }


    /**
     * Função responsável por retornar um array com todos os atributos das classes da hierarquia
     * @todos Fazer funcionar isso aqui
     * @param $obj
     * @return array
     */
    public function retornaNomeAtributosClassesMaes($obj)
    {
        $atributos = [];
        for ($i = 0;
             $i < count(self::retornaClassesMaes($obj));
             $i++) {
            $atributos[$i] = array(self::retornaClassesMaes($obj)[$i] =>
                self::pegaAtributosDoObjeto(self::retornaClassesMaes($obj)[$i]));
        }

        return $atributos;
    }

    /**
     * Função responsável por retornar os comentários dos atributos do objeto em questão.
     * @param $obj
     * @return array
     */
    public function retornaComentariosAtributos($obj)
    {
        $arrAttrNames = self::pegaAtributosDoObjeto($obj);
        $arrAttrComm = array();
        for ($i = 0; $i < count($arrAttrNames); $i++) {

            $reflectionAttr = new ReflectionProperty($obj, $arrAttrNames[$i]);

            $arrAttrComm[$arrAttrNames[$i]] = $reflectionAttr->getDocComment();
        }

        //TODO: FAZER O FOR PARA PEGAR TODOS ATRIBUTOS, PASSAR NO SEGUNDO PARAMETRO;
        return $arrAttrComm;
    }


}