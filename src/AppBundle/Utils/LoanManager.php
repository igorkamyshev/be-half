<?php

namespace AppBundle\Utils;


use AppBundle\Entity\Band;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class LoanManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $data
     * @return User
     *
     * $data =
     *  [
     *      'chatId' => ...,
     *      'name'   => ...,
     *  ]
     */
    public function getOrCreateUser(array $data) {
        $user = null;

        if (isset($data['chatId'])) {
            $user = $this->em
                ->getRepository(User::class)
                ->findOneBy(['telegramChatId' => $data['chatId']]);
        }
        // TODO: добавить возможности создавать пользователя другими путями

        if (!$user) {
            $user = (new User())
                ->setTelegramChatId($data['chatId'])
                ->setName($data['name']);

            $this->em->persist($user);

            $this->em->flush();
        }

        return $user;
    }

    /**
     * @param User $user
     * @return Band
     */
    public function createBand(User $user) {
        $band = (new Band())->addMember($user);

        $this->em->persist($band);

        $user->setBand($band);

        $this->em->flush();

        return $band;
    }
}