<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findByPage(int $page, int $maxResult, UserInterface $user)
    {
        $firstResult = ($page - 1) * $maxResult;

        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param UserInterface $user
     *
     * @param $maxResult
     * @return float|int
     *
     * @throws NonUniqueResultException
     */
    public function findMaxNumberOfPage(UserInterface $user, $maxResult)
    {
        $req = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($req / $maxResult);
    }
}
