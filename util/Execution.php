<?php
require_once 'Internationalization.php';

class Execution
{

    private $time;

    function getTime()
    {
        return microtime(TRUE);
    }

    /* Calculate start time */
    function startExec()
    {
        $this->time = $this->getTime();
    }


    function endExec()
    {

        $finalTime = $this->getTime();
        $execTime = $finalTime - $this->time;
        return number_format($execTime, 6) . Internationalization::translate("seconds");
    }
}

?>