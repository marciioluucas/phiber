<?php
require_once 'JsonReader.php';
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 13:46
 */
class Internationalization
{

    public static function translate($reference) {
        $languageSettedInConfig = JsonReader::read('../phiber_config.json')->phiber->language;
        $lang = JsonReader::read("../lang/$languageSettedInConfig.json");
        return $lang->phiber_lang->$reference;
    }

}

echo Internationalization::translate(0);