<?php

namespace app;

use Exception;

class RulesException extends Exception
{
    public function errorMessage(): string
    {
        return 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': <b>'.$this->getMessage().'</b>';
    }
}
