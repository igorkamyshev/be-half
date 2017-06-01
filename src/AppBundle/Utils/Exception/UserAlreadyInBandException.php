<?php

namespace AppBundle\Utils\Exception;


use AppBundle\Entity\Band;

class UserAlreadyInBandException extends \Exception
{
    public $band;

    public function __construct(Band $band, $message = null, $code = 0, Exception $previous = null) {
        $this->band = $band;

        parent::__construct($message, $code, $previous);
    }
}