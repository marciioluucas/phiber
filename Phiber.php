<?php
/**
 * Classe criada por Márcio Lucas R de Oliveira (lukee)
 * Data: 16/03/17
 * Hora: 19:07
 */
namespace phiber;
use phiber\bin\persistence\PhiberPersistence;
//include_once 'PhiberAutoload.php';

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
class Phiber
{

    /**
     * Método responsável por retornar uma instancia da classe PhiberPersistence,
     * que é responsável pela persistencia dos dados. (CREATE, RETREAVE, UPDATE, DELETE)
     * @return PhiberPersistence
     */
    public function openPersist($object)
    {
//        new PhiberAutoload();
        return new PhiberPersistence($object);
    }

}
