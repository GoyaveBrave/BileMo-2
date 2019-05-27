<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function findByPage(int $page, int $maxResult)
    {
        $firstResult = ($page - 1) * $maxResult;

        return $this->createQueryBuilder('p')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $maxResult
     *
     * @return float
     *
     * @throws NonUniqueResultException
     */
    public function findMaxNumberOfPage($maxResult)
    {
        $req = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($req / $maxResult);
    }
}
