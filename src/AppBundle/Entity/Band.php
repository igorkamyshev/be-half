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
     * @OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="band")
     * @var ArrayCollection
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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
}