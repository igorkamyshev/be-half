<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="user")
     * @var ArrayCollection
     */
    private $transactions;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $balance = 0;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

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

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $transaction
     * @return User
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transactions->add($transaction);
        return $this;
    }

    /**
     * @param Transaction $transaction
     * @return User
     */
    public function removeTransaction(Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
        return $this;
    }
}