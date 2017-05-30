<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="Identity"")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="group", fetch="EAGER", cascade={"persist", "merge", "refresh", "remove"})
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
     * @return Group
     * @throws \Exception
     * TODO: Сделать номральную обработку лишнего мебера
     */
    public function addMember(User $member)
    {
        if (count($this->members) > 1) {
            throw new \Exception();
        }

        $this->members->add($member);
        return $this;
    }
}