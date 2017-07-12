<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */

class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Band", inversedBy="transactions")
     * @ORM\JoinColumn(name="band_id", referencedColumnName="id")
     * @var Band
     */
    private $band;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="transactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $amount;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $comment;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return Transaction
     */
    public function setBand($band)
    {
        $this->band = $band;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param user $user
     * @return Transaction
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Transaction
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}