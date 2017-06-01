<?php

namespace AppBundle\Utils\Exception;


use AppBundle\Entity\Band;
use Exception;

class UserAlreadyInBandException extends \Exception
{
    private $band;

    public function __construct(Band $band, $message = 'Вы уже состоите в группе.', $code = 0, Exception $previous = null) {
        $this->band = $band;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Band
     */
    public function getBand()
    {
        return $this->band;
    }

    /**
     * @param Band $band
     * @return UserAlreadyInBandException
     */
    public function setBand($band)
    {
        $this->band = $band;
        return $this;
    }

}