<?php

namespace AppBundle\Utils\Exception;


use AppBundle\Entity\Band;
use Exception;

class BandNotExistException extends \Exception
{
    private $bandId;

    public function __construct($bandId, $message = 'Группа с таким идентификатором не существует.', $code = 0, Exception $previous = null) {
        $this->bandId = $bandId;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getBandId()
    {
        return $this->bandId;
    }

    /**
     * @param int $bandId
     * @return UserAlreadyInBandException
     */
    public function setBandId($bandId)
    {
        $this->bandId = $bandId;
        return $this;
    }

}