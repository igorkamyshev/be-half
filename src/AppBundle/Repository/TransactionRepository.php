<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Band;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class TransactionRepository extends EntityRepository
{
    const ENTITY_ALIAS = 'entity';

    const DEFAULT_LAST_ENTITIES_LIMIT = 10;

    public function findLastByUser(User $user, $limit = self::DEFAULT_LAST_ENTITIES_LIMIT)
    {
        return $this->findLastByBand($user->getBand(), $limit);
    }

    public function findLastByBand(Band $band, $limit = self::DEFAULT_LAST_ENTITIES_LIMIT)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder(self::ENTITY_ALIAS);

        $result = $qb
            ->where(self::ENTITY_ALIAS . '.band = :band')
            ->setParameter('band', $band)
            ->orderBy(self::ENTITY_ALIAS . '.id', 'DESC')
            ->getQuery()
            ->setMaxResults($limit)
            ->getResult();

        return $result;
    }
}