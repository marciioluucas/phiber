<?php
require_once '../bin/Phiber.php';

/**
 * Class Usuario
 *
 */
class Usuario
{

    /**
     * @_type=int
     * @_size=11
     * @_primaryKey=false
     * @_notNull=true
     * @_default=none
     * @_autoIncrement=false
     */
    private $id;

    /**
     * @_type=varchar
     * @_size=56
     * @_primaryKey=false
     * @_notNull=false
     * @_default=none
     * @_autoIncrement=false
     */
    private $nome;


    /**
     * @_type=int
     * @_size=15
     * @_primaryKey=true
     * @_notNull=true
     * @_default=5
     * @_autoIncrement=false
     */
    private $email;

    /**
     * @_type=int
     * @_size=57
     * @_primaryKey=false
     * @_notNull=true
     * @_default=none
     * @_autoIncrement=false
     */
    private $cpf;





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
//$pPersist = Phiber::openPersist()->create;
//$pPersist::create($usuario);
