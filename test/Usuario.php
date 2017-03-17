<?php
require_once '../bin/Phiber.php';

/**
 * Class Usuario
 *
 */
class Usuario extends Phiber
{

    /**
     * @_name=id
     * @_type=varchar
     * @_size=55
     * @_primaryKey=true
     */
    private $id;

    /**
     * @_name=nome
     * @_type=varchar
     * @_size=56
     * @_primaryKey=false
     */
    private $nome;
    /**
     * @_name=email
     * @_type=varchar
     * @_size=55
     * @_primaryKey=false
     */
    private $email;
    /**
     * @_type=varchar
     * @_size=55
     * @_primaryKey=false
     */
    private $cpf;
    /**
     * @_type=varchar
     * @_size=55
     * @_primaryKey=false
     */
    private $senha;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


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

//$usuario = new Usuario();
//$usuario->setNome("Lucas");
//$usuario->setEmail("123@123.com");
//$usuario->setCpf("123");
//$usuario->setSenha("123123123");
//$pPersist = Phiber::openPersist();
//print_r($pPersist::create($usuario));
