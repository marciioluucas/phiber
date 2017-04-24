<?php
namespace test;
/**
 * Class Usuario
 *
 */
class Usuario
{

    /**
     * @_type=int
     * @_size=11
     * @_primaryKey=true
     * @_notNull=true
     * @_default=none
     * @_autoIncrement=true
     */
    private $id;

    /**
     * @_type=varchar
     * @_size=158
     * @_primaryKey=false
     * @_notNull=true
     * @_default=none
     * @_autoIncrement=false
     */
    private $nome;


    /**
     * @_type=varchar
     * @_size=170
     * @_primaryKey=false
     * @_notNull=true
     * @_default=olamundo
     * @_autoIncrement=false
     */
    private $email;

    /**
     * @_type=varchar
     * @_size=15
     * @_primaryKey=false
     * @_notNull=true
     * @_default=none
     * @_autoIncrement=false
     */
    private $cpf;


    /**
     * @_type=varchar
     * @_size=15
     * @_primaryKey=false
     * @_notNull=true
     * @_default=none
     * @_autoIncrement=false
     */
    private $cnpj;

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

    /**
     * @return mixed
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

}



//print_r(\phiber\Phiber::openPersist()->select($u, [
//    "fields" => ["nome", "email"],
//    "conditions" => [
//        [
//            "nome",
//            "LIKE",
//            "%Marcio Lucas%"
//        ],
//        [
//            "email",
//            "=",
//            "marciioluucas@gmail.com"
//        ]
//    ],
//    "conjunctions" =>
//        [
//            "and"
//        ],
//    "one_result" => false
//]));

require '../Phiber.php';
include_once '../vendor/autoload.php';
$u = new Usuario();
use bin\queries\Restrictions;
use Phiber;
$criteria = Phiber::openPersist();
//$rNome = Restrictions::biggerThen("id", 10);
//$rEmail = Restrictions::eq("email", "marciioluucas@gmail.com");
//$nomeAndEmail = Restrictions:: and ($rNome, $rEmail);
//$rUsuario = Restrictions::like("usuario", "marciioluucas");
//$rSenha = Restrictions::eq("senha", "s123");
//
//$usuarioAndSenha = Restrictions:: or ($rUsuario, $rSenha);

$u->setNome("Jonas do amor");
$u->setEmail("amor@jonas.com");
$criteria->add(Restrictions::eq("id",4));
print_r($criteria->select($u));

