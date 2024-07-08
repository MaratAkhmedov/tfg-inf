<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Property;
use App\Entity\PropertyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }
    
    /**
     * @return Property[] Returns an array of Property objects
     */
    public function buildFindByCityAndRoomQuery(City $city, PropertyType $type, mixed $searchCriteria): QueryBuilder
    {
        $searchCriteria = $this->buildSearchCriteria($searchCriteria);
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.address', 'pa')
            ->andWhere('pa.city = :city')
            ->andWhere('p.type = :type')
            ->setParameter('city', $city)
            ->setParameter('type', $type);
        
        $queryBuilder->addCriteria($searchCriteria);

        return $queryBuilder;
    }

    private function buildSearchCriteria(mixed $searchCriteria): Criteria
    {
        $expressions = [];
        $expressionBuilder = Criteria::expr();
        if($priceMin = $searchCriteria['priceMin'] ?? null) {
            $expressions[] = $expressionBuilder->gte('p.price', $priceMin);
        }
        if($priceMax = $searchCriteria['priceMax'] ?? null) {
            $expressions[] = $expressionBuilder->lte('p.price', $priceMax);
        }
        if($squareMin = $searchCriteria['squareMin'] ?? null) {
            $expressions[] = $expressionBuilder->gte('p.square', $squareMin);
        }
        if($squareMax = $searchCriteria['squareMax'] ?? null) {
            $expressions[] = $expressionBuilder->lte('p.square', $squareMax);
        }
        $expression = $expressionBuilder->andX(...$expressions);
        return new Criteria($expression);
    }

    //    /**
    //     * @return Property[] Returns an array of Property objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Property
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
