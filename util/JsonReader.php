<?php

/**
 * Created by PhpStorm.
 * User: marci
 * Date: 14/02/2017
 * Time: 20:23
 */
class JsonReader
{
    public static function read($arquivo){
            $info = file_get_contents($arquivo);
            $lendo = json_decode($info);
            return $lendo;
        }
}
//print_r(JsonReader::read("../phiber_config.json")->phiber);