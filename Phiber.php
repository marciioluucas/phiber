<?php

namespace Phiber;

use Phiber\ORM\Persistence\PhiberPersistence;
use StdClass;

/**
 *Constante que define a base de onde está localizado o projeto
 */
define("BASE_DIR",str_replace('\\', '/', dirname(__FILE__)));

/**
 * A classe Phiber é a responsável por ser a classe módulo entre as funcionalidades do
 * Phiber, é por ela que você chamará desde a classe persistencia até a classe de encriptação.
 *
 * @package phiber
 */
class Phiber extends PhiberPersistence
{
    /**
     * Phiber constructor.
     * 
     * @param string|StdClass $object
     */
    public function __construct($object = "")
    {
        parent::__construct($object);
    }

    /**
     * Método opcional responsável por retornar uma instância da classe PhiberPersistence,
     * que é responsável pela persistencia dos dados. (CREATE, RETREAVE, UPDATE, DELETE)
     * 
     * @deprecated
     * @param string|\stdClass $object
     * @return PhiberPersistence
     */
    public function openPersist($object = "")
    {
        return new PhiberPersistence($object);
    }
}
