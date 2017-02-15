<?php

/**
 * Created by PhpStorm.
 * User: Márcio Lucas
 * E-mail: marciioluucas@gmail.com
 * Date: 08/11/2016
 * Time: 10:31
 */
class FuncoesMensagens
{
    public static function geraJSONMensagem($mensagem, $tipo)
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode(array("mensagem" => $mensagem, "tipo" =>$tipo));
    }

    public static function geraMensagem($mensagem, $tipo) {
        return "mensagem=".urlencode($mensagem)."&tipo=".urlencode($tipo);
    }
}