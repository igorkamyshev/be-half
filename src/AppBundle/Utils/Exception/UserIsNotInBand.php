<?php

namespace AppBundle\Utils\Exception;


use AppBundle\Entity\Band;
use Exception;

class UserIsNotInBandException extends \Exception
{
    public function __construct($message = 'Вы не состоите в группе.', $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}