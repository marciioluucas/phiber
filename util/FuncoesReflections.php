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
    /**
     * @var array
     */
    private static $p;

    /**
     * Construtor da classe FuncoesReflections
     * @param $p
     */
    public function __construct()
    {
        $this->p = [];
    }


//    public static function pegaAtributoDoObjeto($obj)
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
    public static function pegaNomesMetodosClasse($obj)
    {
        $aux = get_class_methods($obj);
        return $aux;
    }

    /**
     * Função responsável por pegar o nome de um atributo espefífico.
     * Caso o atributo pesquisado não exista, a função retornará falso.
     * @param $obj
     * @param $nomeAtributo
     * @return bool|string
     * @throws Exception
     */
    public static function pegaNomeAtributoEspecifico($obj, $nomeAtributo)
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
    public static function pegaAtributosDoObjeto($obj)
    {
        $reflectionClass = new ReflectionClass($obj);
        $propriedades = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        for ($i = 0; $i < count($propriedades); $i++) {
            self::$p[$i] = $propriedades[$i]->name;
        }
//        if ($reflectionClass->getParentClass() != null) {
//            self::pegaAtributoDoObjeto($reflectionClass->getParentClass());
//        }
        return self::$p;
    }

    /**
     * Função responsável por pegar o valor de um atributo específico do objeto.
     * @param $obj
     * @param $nomeAtributo
     * @return mixed
     */
    public static function pegaValorAtributoEspecifico($obj, $nomeAtributo)
    {
        $nomeAtributos = $nomeAtributo;
        $reflectionClass = new ReflectionClass(self::pegaNomeClasseObjeto($obj));
        $reflectionProperty = $reflectionClass->getProperty($nomeAtributos);
        $reflectionProperty->setAccessible(true);
        $valoresAtributosFinal = $reflectionProperty->getValue($obj);
        return $valoresAtributosFinal;
    }

    /**
     * Função responsável por pegar o nome da classe do objeto em questão.
     * @param $obj
     * @return string
     */
    public static function pegaNomeClasseObjeto($obj)
    {
        $reflectionClass = new ReflectionClass($obj);
        return $reflectionClass->getShortName();
    }

    /**
     * Injeta valores nos atributos do objeto em questão.
     * @param \Object $obj
     * @param array $atributos
     * @param array $valor
     */
    public static function injetaValorAtributo($obj, $atributos = [], $valor = [])
    {
        $reflectionClass = new ReflectionClass($obj);
        if (count($atributos) >= 0) {
            for ($i = 0; $i < count($atributos); $i++) {
                $reflectionProperty = $reflectionClass->getProperty($atributos[$i]);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($obj, $valor[$i]);
            }
        }
    }

    /**
     * Função responsável por verifidar se a classe é filha de alguma outra classe,
     * se caso não for. A função retornará falso.
     * @param \Object $obj
     * @return bool
     */
    public static function verificaSeEClasseFilha($obj)
    {
        $class = new ReflectionClass($obj);
        if ($class->getParentClass()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Função responsável por retornar os valores dos atributos das classes mães,
     * se as mesmas existirem, se caso a classe em questão não for uma classe filha, a função retornará
     * false.
     * @param $obj
     * @return bool|array
     */
    public static function retornaValoresAtributosClassesMaes($obj)
    {
        if(self::verificaSeEClasseFilha($obj)){
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
    public static function retornaClassesMaes($obj)
    {
        $class = new ReflectionClass($obj);

        $parents = [];
        $parent = "";
        if(self::verificaSeEClasseFilha($obj)){
            while($class->getParentClass()) {
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
    public static function pegaValoresAtributoDoObjeto($obj)
    {
        $nomeAtributos = self::pegaAtributosDoObjeto($obj);
        $valoresAtributosFinal = [];
        $reflectionClass = new ReflectionClass($obj);
        for ($i = 0; $i < count($nomeAtributos); $i++) {
            $reflectionProperty = $reflectionClass->getProperty($nomeAtributos[$i]);
            $reflectionProperty->setAccessible(true);
            $valoresAtributosFinal[$i] = $reflectionProperty->getValue($obj);
        }
        return $valoresAtributosFinal;
    }


    /**
     * Função responsável por retornar um array com todos os atributos das classes da hierarquia
     * @todos Fazer funcionar isso aqui
     * @param $obj
     * @return array
     */
    public static function retornaNomeAtributosClassesMaes($obj)
    {
        $atributos = [];
        for ($i = 0;
             $i < count(FuncoesReflections::retornaClassesMaes($obj));
             $i++) {
            $atributos[$i] = array(FuncoesReflections::retornaClassesMaes($obj)[$i] =>
                FuncoesReflections::pegaAtributosDoObjeto(FuncoesReflections::retornaClassesMaes($obj)[$i]));
        }

        return $atributos;
    }

    /**
     * Função responsável por retornar os comentários dos atributos do objeto em questão.
     * @param $obj
     * @return array
     */
    public static function retornaComentariosAtributos($obj)
    {
        $arrAttributesNames = self::pegaAtributosDoObjeto($obj);
        $arrAttributesComments = array();
        for($i= 0; $i < count($arrAttributesNames); $i++){

            $reflectionAttribute = new ReflectionProperty($obj, $arrAttributesNames[$i]);

           $arrAttributesComments[$arrAttributesNames[$i]] = $reflectionAttribute->getDocComment();
        }

    //TODO: FAZER O FOR PARA PEGAR TODOS ATRIBUTOS, PASSAR NO SEGUNDO PARAMETRO;
//        print_r($arrComments);
        return $arrAttributesComments;
    }


}
//
//require_once '../model/Usuario.php';
//require_once '../model/Funcionario.php';
//require_once '../model/Cargo.php';
//$u = new Funcionario();
//$u->setNome("Marcio Lucas");
//$u->setCpf("03794335163");
//$c = new Cargo();
//$c->setNome("PAMONHA");
////print_r(FuncoesReflections::pegaValoresAtributoDoObjeto($u));
////print_r(FuncoesReflections::pegaValoresAtributoDoObjeto($u));
//
//print_r(FuncoesReflections::retornaNomeAtributosClassesMaes($u));