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
    private $maxResult = 5;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function findByPage(int $page)
    {
        $firstResult = ($page - 1) * $this->maxResult;
        return $this->createQueryBuilder('p')
            ->setFirstResult($firstResult)
            ->setMaxResults($this->maxResult)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findMaxNumberOfPage()
    {
        $req = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($req / $this->maxResult);
    }
}
