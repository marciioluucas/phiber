<?php
require_once '../bin/Phiber.php';
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 14/02/2017
 * Time: 22:09
 */
class Usuario extends Phiber
{

    private $nome;
    private $email;
    private $cpf;
    private $senha;

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}

$usuario = new Usuario();
$usuario->setNome("Lucas");
$usuario->setEmail("123@123.com");
$usuario->setCpf("123");
$usuario->setSenha("123123123");
$pPersist = Phiber::openPersist();
print_r($pPersist::create($usuario));
