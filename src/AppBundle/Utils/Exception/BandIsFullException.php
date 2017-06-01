<?php

namespace AppBundle\Utils\Exception;


use AppBundle\Entity\Band;
use Exception;

class BandIsFullException extends \Exception
{
    private $band;

    public function __construct(Band $band, $message = 'В данной группе уже состит два человека.', $code = 0, Exception $previous = null) {
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
    public function setBand(Band $band)
    {
        $this->band = $band;
        return $this;
    }

}