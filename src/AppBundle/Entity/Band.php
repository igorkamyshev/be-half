<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Band
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="band")
     * @var ArrayCollection
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="band")
     * @var ArrayCollection
     */
    private $transactions;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param User $member
     * @return Band
     * TODO: Запретить добавлять больше двух членов
     */
    public function addMember(User $member)
    {
        $this->members->add($member);
        return $this;
    }

    /**
     * @param User $member
     * @return $this
     */
    public function removeMember(User $member)
    {
        $this->members->removeElement($member);
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
     * @return Band
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions->add($transaction);
        return $this;
    }

    /**
     * @param Transaction $transaction
     * @return Band
     */
    public function removeTransaction(Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
        return $this;
    }
}