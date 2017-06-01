<?php

namespace AppBundle\Utils;


use AppBundle\Entity\Band;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use AppBundle\Utils\Exception\BandNotExistException;
use AppBundle\Utils\Exception\UserAlreadyInBandException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @throws UserAlreadyInBandException
     */
    public function createBand(User $user) {
        $band = $user->getBand();
        if ($band) {
            throw new UserAlreadyInBandException($band);
        }

        $band = (new Band())->addMember($user);

        $this->em->persist($band);

        $user->setBand($band);

        $this->em->flush();

        return $band;
    }

    /**
     * @param Band $band
     * @param User $user
     * @return Band
     * @throws UserAlreadyInBandException
     */
    public function joinBand(Band $band, User $user)
    {
        if ($user->getBand()) {
            throw new UserAlreadyInBandException($user->getBand());
        }

        $band->addMember($user);
        $user->setBand($band);

        $this->em->flush();

        return $band;
    }

    public function joinBandById($bandId, $user)
    {
        $band = $this->em->getRepository(Band::class)->find($bandId);
        if ($band) {
            return $this->joinBand($band, $user);
        } else {
            throw new BandNotExistException($bandId);
        }
    }

    public function createTransaction(User $user, $amount)
    {
        $transaction = (new Transaction())
            ->setAmount($amount)
            ->setUser($user);

        $this->em->persist($transaction);

        $user->addTransaction($transaction);
        $user->setBalance($user->getBalance() + $transaction->getAmount());

        /** @var User $partner */
        $partner = $user->getBand()->getPartner($user);

        $partner->setBalance($partner->getBalance() - $transaction->getAmount());

        $this->em->flush();
    }
}