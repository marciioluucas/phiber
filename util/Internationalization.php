<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace util;

/**
 * Classe responsável pela internacionalização do projeto.
 * @package util
 */
class Internationalization
{


    private $msgTranslated;

    /**
     * Usa a referencia de linguagem no arquivo json para traduzir.
     * @param String $reference
     */
    public function __construct(String $reference)
    {
        $languageSettedInConfig = JsonReader::read(BASE_DIR . '/phiber_config.json')->phiber->language;
        $lang = JsonReader::read(BASE_DIR . "/lang/$languageSettedInConfig.json");
        $this->msgTranslated = $lang->phiber_lang->$reference . "";
    }

    function __toString()
    {
        return $this->msgTranslated;
    }


}