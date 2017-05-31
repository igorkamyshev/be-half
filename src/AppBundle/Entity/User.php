<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="telegram_chat_id", nullable=true)
     * @var int
     */
    private $telegramChatId;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Band", inversedBy="members")
     * @ORM\JoinColumn(name="band_id", referencedColumnName="id")
     * @var Band
     */
    private $band;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTelegramChatId()
    {
        return $this->telegramChatId;
    }

    /**
     * @param int $telegramChatId
     * @return User
     */
    public function setTelegramChatId($telegramChatId)
    {
        $this->telegramChatId = $telegramChatId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @return User
     */
    public function setBand($band)
    {
        $this->band = $band;
        return $this;
    }
}