<?php
require_once '../bin/Phiber.php';

/**
 * Class Usuario
 * @_table=tbl_usuario
 */
class Usuario extends Phiber
{

    /**
     * @_coluna
     * @_name=cl_nome
     * @_type=varchar
     * @_tamanho=55
     * @_primaryKey
     */
    private $nome;
    /**
     * @_coluna
     * @_name=cl_email
     * @_type=varchar
     * @_tamanho=55
     */
    private $email;
    /**
     * @_coluna
     * @_name=cl_cpf
     * @_type=varchar
     * @_tamanho=55
     */
    private $cpf;
    /**
     * @_coluna
     * @_name=cl_senha
     * @_type=varchar
     * @_tamanho=55
     */
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

//$usuario = new Usuario();
//$usuario->setNome("Lucas");
//$usuario->setEmail("123@123.com");
//$usuario->setCpf("123");
//$usuario->setSenha("123123123");
//$pPersist = Phiber::openPersist();
//print_r($pPersist::create($usuario));
