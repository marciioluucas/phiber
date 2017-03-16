<?php
require_once 'Link.php';
require_once '../util/FuncoesString.php';
require_once '../util/FuncoesReflections.php';
require_once '../util/JsonReader.php';
require_once 'IPhiber.php';

/**
 * Created by PhpStorm
 * User: Lukee
 * Date: 20/10/2016
 * Time: 22:14
 */
class Phiber implements IPhiber
{

    /**
     * @param $obj
     * @return mixed
     * @throws Exception
     * Faz a criação de um registro no banco com os dados de um objeto.
     */
    public function create($obj)
    {
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
            $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
            $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);
            $camposNome = [];
            $camposValores = [];

            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null) {
                    $camposNome[$i] = $campos[$i];
                }
            }

            for ($i = 0; $i < count($camposV); $i++) {
                if ($camposV[$i] != null) {
                    $camposValores[$i] = $camposV[$i];
                }
            }
            $camposNome = array_values($camposNome);
            $camposValores = array_values($camposValores);
            $sqlInsert = "INSERT INTO $tabela (";
            for ($i = 0; $i < count($camposNome); $i++) {
                if ($i != count($camposNome) - 1) {
                    $sqlInsert .= $camposNome[$i] . ", ";
                } else {
                    $sqlInsert .= $camposNome[$i] . ") VALUES (";
                }
            }

            for ($j = 0; $j < count($camposNome); $j++) {
                if ($j != count($camposNome) - 1) {
                    $sqlInsert .= ":" . $camposNome[$j] . ", ";
                } else {
                    $sqlInsert .= ":" . $camposNome[$j] . ")";
                }
            }
            echo $sqlInsert;
            if (JsonReader::read("../phiber_config.json")->phiber->execute_querys) {
                $pdo = Link::getConnection()->prepare($sqlInsert);
                for ($i = 0; $i < count($camposNome); $i++) {
                    $pdo->bindValue($camposNome[$i], $camposValores[$i]);
                }

                if ($pdo->execute()) {
                    return true;
                };
            } else {
                return $sqlInsert;
            }

        } catch (Exception $e) {
            throw new Exception("Erro ao processar query", 0, $e);
        }
        finally {
            return false;
        }

    }


    /**
     * @param $obj
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function porId($obj)
    {
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
            $sqlSelect = "SELECT * from $tabela WHERE pk_" . $tabela . " = " . FuncoesReflections::pegaValorAtributoEspecifico($obj, "pk_$tabela");
            $pdo = Link::getConnection()->prepare($sqlSelect);
            $pdo->execute();
            return $pdo->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erro ao processar query: ", 2, $e);
        }

    }

    /**
     * @param $obj
     * @return mixed
     * @throws Exception
     */
    public function update($obj, $id)
    {
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
            $campos = FuncoesReflections::pegaAtributosDoObjeto($obj);
            $camposV = FuncoesReflections::pegaValoresAtributoDoObjeto($obj);

            $camposNome = [];
            $camposValores = [];
            for ($i = 0; $i < count($campos); $i++) {
                if ($camposV[$i] != null || $camposV[$i] != "") {
                    $camposNome[$i] = $campos[$i];
                }
            }

            for ($i = 0; $i < count($camposV); $i++) {
                if ($camposV[$i] != null || $camposV[$i] != "") {
                    $camposValores[$i] = $camposV[$i];
                }
            }
            $camposNome = array_values($camposNome);
            $camposValores = array_values($camposValores);

            $sqlUpdate = "UPDATE $tabela SET ";

            for ($i = 0; $i < count($camposNome); $i++) {
                if ($i != count($camposNome) - 1) {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i] . ", ";
                } else {
                    $sqlUpdate .= $camposNome[$i] . " = :" . $camposNome[$i] . " WHERE pk_" . $tabela . " = " . $id;
                }
            }
            $pdo = Link::getConnection()->prepare($sqlUpdate);
            for ($i = 0; $i < count($camposNome); $i++) {
                $pdo->bindValue($camposNome[$i], $camposValores[$i]);
            }
//            echo FuncoesMensagens::geraJSONMensagem($camposValores, "sucesso");
//            print_r($sqlUpdate);
            if ($pdo->execute()) {

                return true;
            } else {
                echo $sqlUpdate;
                return false;
            }
//                $pdo->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao processar query", $e);
        }
    }

    /**
     * @param $obj
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($obj, $id)
    {
        try {
            $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
            $sqlUpdate = "DELETE FROM $tabela WHERE pk_" . $tabela . " = :pk_" . $tabela;
            $pdo = Link::getConnection()->prepare($sqlUpdate);
            $pdo->bindValue("pk_" . $tabela, $id);
            return $pdo->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao processar query", $e);
        }
    }

    /**
     * @param $obj
     * @param $condicoes
     * @return string
     */
    public function quantidadeRegistros($obj, $condicoes = [])
    {
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));
        $nomeCampos = [];
        $condicoesComIndexInt = array_keys($condicoes);
        for ($i = 0; $i < count($condicoes); $i++) {
            $nomeCampos[$i] = $condicoesComIndexInt[$i];
        }
        $valoresCampos = [];
        for ($j = 0; $j < count($condicoes); $j++) {
            $valoresCampos[$j] = $condicoes[$nomeCampos[$j]];
        }
        $sql = "SELECT * FROM $tabela WHERE ";

        for ($x = 0; $x < count($nomeCampos); $x++) {
            if ($x != count($nomeCampos) - 1) {
                $sql .= $nomeCampos[$x] . " = ? and ";
            } else {
                $sql .= $nomeCampos[$x] . " = ?";
            }
        }
        $pdo = Link::getConnection()->prepare($sql);
        for ($i = 1; $i <= count($nomeCampos); $i++) {
            $pdo->bindValue($i, $valoresCampos[$i - 1]);
        }
        $pdo->execute();
        return $pdo->rowCount();
    }

    public function buscaPorCondicoes($obj, $condicoes, $retornaPrimeiroValor = false)
    {
        $tabela = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj));

        $nomeCampos = [];

        if ($condicoes != null) {

            $condicoesComIndexInt = array_keys($condicoes);

            for ($i = 0; $i < count($condicoes); $i++) {
                $nomeCampos[$i] = $condicoesComIndexInt[$i];
            }

            $valoresCampos = [];

            for ($j = 0; $j < count($condicoes); $j++) {
                if ($condicoes[$nomeCampos[$j]] != "") {
                    $valoresCampos[$j] = $condicoes[$nomeCampos[$j]];
                }
            }

            $sql = "SELECT * FROM $tabela WHERE ";
            $nomeCamposNovo = [];
            for ($x = 0; $x < count($nomeCampos); $x++) {
                if ($x != count($nomeCampos) - 1) {
                    if ($condicoes[$nomeCampos[$x]] != "") {
                        if (count($valoresCampos) > 1) {
                            $sql .= $nomeCampos[$x] . " = ? and ";
                        } else {
                            $sql .= $nomeCampos[$x] . " = ?";
                        }
                        $nomeCamposNovo[$x] = $nomeCampos[$x];
                    }
                } else {
                    if ($condicoes[$nomeCampos[$x]] != "") {
                        $sql .= $nomeCampos[$x] . " = ?";
                        $nomeCamposNovo[$x] = $nomeCampos[$x];
                    }
                }
            }
            $nomeCamposNovo = array_values($nomeCamposNovo);
            $pdo = Link::getConnection()->prepare($sql);
            $valoresCampos = array_values($valoresCampos);

            for ($i = 1; $i <= count($nomeCamposNovo); $i++) {
                $pdo->bindValue($i, $valoresCampos[$i - 1]);
            }
            $pdo->execute();

            if ($retornaPrimeiroValor) {
                return $pdo->fetch(PDO::FETCH_ASSOC);
            } else {
                return $pdo->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            $sql = "SELECT * FROM $tabela";
            $pdo = Link::getConnection()->prepare($sql);
            $pdo->execute();
            if ($retornaPrimeiroValor) {
                return $pdo->fetch(PDO::FETCH_ASSOC);
            } else {
                return $pdo->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }

    public function innerJoin($obj1, $obj2, $condicoes = null, $retornaSoPrimeiro = false, $campos = null)
    {
        $tabela1 = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj1));
        $tabela2 = FuncoesString::paraCaixaBaixa(FuncoesReflections::pegaNomeClasseObjeto($obj2));

        $nomeCampos = [];

        if ($condicoes != null) {
            $condicoesComIndexInt = array_keys($condicoes);
            for ($i = 0; $i < count($condicoes); $i++) {
                $nomeCampos[$i] = $condicoesComIndexInt[$i];
            }
            $valoresCampos = [];
            for ($j = 0; $j < count($condicoes); $j++) {
                if ($condicoes[$nomeCampos[$j]] != "") {
                    $valoresCampos[$j] = $condicoes[$nomeCampos[$j]];
                }
            }
            if ($campos == null) {
                $sql = "SELECT * FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` where ";
            } else {
                $strCampos = "";
                for ($i = 0; $i < count($campos); $i++) {
                    if ($i != count($campos) - 1) {
                        $strCampos .= $campos[$i] . ", ";
                    } else {
                        $strCampos .= $campos[$i] . " ";
                    }
                }
                $sql = "SELECT $strCampos FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` where ";
            }
            $nomeCamposNovo = [];
            for ($x = 0; $x < count($nomeCampos); $x++) {
                if ($x != count($nomeCampos) - 1) {
                    if ($condicoes[$nomeCampos[$x]] != "") {
                        if (count($valoresCampos) > 1) {
                            $sql .= $nomeCampos[$x] . " = ? and ";
                        } else {
                            $sql .= $nomeCampos[$x] . " = ?";
                        }
                        $nomeCamposNovo[$x] = $nomeCampos[$x];
                    }
                } else {
                    if ($condicoes[$nomeCampos[$x]] != "") {
                        $sql .= $nomeCampos[$x] . " = ?";
                        $nomeCamposNovo[$x] = $nomeCampos[$x];
                    }
                }
            }
            $nomeCamposNovo = array_values($nomeCamposNovo);
            $pdo = Link::getConnection()->prepare($sql);
            $valoresCampos = array_values($valoresCampos);

            for ($i = 1; $i <= count($nomeCamposNovo); $i++) {
                $pdo->bindValue($i, $valoresCampos[$i - 1]);
            }
            $pdo->execute();
            if ($retornaSoPrimeiro) {
                return $pdo->fetch(PDO::FETCH_ASSOC);
            } else {
                return $pdo->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            if ($campos == null) {

                $sql = "SELECT * FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` ";
            } else {
                $strCampos = "";
                for ($i = 0; $i < count($campos); $i++) {
                    if ($i != count($campos) - 1) {
                        $strCampos .= $campos[$i] . ", ";
                    } else {
                        $strCampos .= $campos[$i] . " ";
                    }
                }
                $sql = "SELECT $strCampos FROM $tabela1 INNER JOIN $tabela2 on `$tabela1`.`fk_$tabela2` = `$tabela2`.`pk_$tabela2` ";
            }
            $pdo = Link::getConnection()->prepare($sql);
            $pdo->execute();
            if ($retornaSoPrimeiro) {
                return $pdo->fetch(PDO::FETCH_ASSOC);
            } else {
                return $pdo->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
}