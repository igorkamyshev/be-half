<?php

namespace AppBundle\Utils\Exception;


use AppBundle\Entity\Band;
use Exception;

class UserDoesntHavePartnerException extends \Exception
{
    public function __construct($message = 'В группе не состоит никого кроме вас.', $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}