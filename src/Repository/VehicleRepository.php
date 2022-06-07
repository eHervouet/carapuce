<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }
    public function VehiculeTriee()
    {
        //en DQL
        $entityManager = $this->getEntityManager();
        $dql ="
            SELECT t
            FROM App\Entity\Vehicle v
            WHERE t.VilleDepart = 'test'
            
        ";
        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(20);

        //version querybuilder
        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->andWhere();
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;

    }
}
