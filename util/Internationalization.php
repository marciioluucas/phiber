<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\util;

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
        $jsonReader = new JsonReader(BASE_DIR . '/phiber_config.json');
        $lang = new JsonReader(BASE_DIR . "/lang/" . $jsonReader->read()->phiber->language . ".json");
        $this->msgTranslated = $lang->read()->phiber_lang->$reference . "";
    }

    public function __toString()
    {
        return $this->msgTranslated;
    }


}