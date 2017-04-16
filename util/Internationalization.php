<?php
namespace util;
/**
 * Created by PhpStorm.
 * User: lukee
 * Date: 17/03/17
 * Time: 13:46
 */
class Internationalization
{

    public static function translate($reference) {
        $languageSettedInConfig = JsonReader::read(BASE_DIR.'/phiber_config.json')->phiber->language;
        $lang = JsonReader::read(BASE_DIR."/lang/$languageSettedInConfig.json");
        return $lang->phiber_lang->$reference;
    }

}