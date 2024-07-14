<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Favorite;
use App\Entity\Property;
use App\Entity\PropertyType;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
    public function findUserProperties(User $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.address', 'pa')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user);

        return $qb;
    }

    /**
     * @return Property[] Returns an array of Property objects
     */
    public function buildFindByCityAndRoomQuery(?City $city, ?PropertyType $type, mixed $searchCriteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.address', 'pa');

        if ($city) {
            $qb->andWhere('pa.city = :city')
                ->setParameter('city', $city);
        }

        if ($type) {
            $qb->andWhere('p.type = :type')
                ->setParameter('type', $type);
        }

        if ($states = $searchCriteria['states'] ?? null) {
            if (!empty($states->toArray())) {
                $qb->andWhere(
                    $qb->expr()->in(
                        'p.id',
                        $this->createQueryBuilder('p2')
                            ->innerJoin('p2.state', 'ps2', Join::WITH, 'ps2 IN (:states)')
                            ->groupBy('p2.id')
                            ->having('COUNT(p2.id) = :stateCount')
                            ->getDQL()
                    )
                );
                $qb->setParameter('states', $states)
                    ->setParameter('stateCount', $states->count());
            }
        }

        if ($rules = $searchCriteria['rules'] ?? null) {
            if (!empty($rules->toArray())) {
                $qb->andWhere(
                    $qb->expr()->in(
                        'p.id',
                        $this->createQueryBuilder('p3')
                            ->innerJoin('p3.rules', 'pr2', Join::WITH, 'pr2 IN (:rules)')
                            ->groupBy('p3.id')
                            ->having('COUNT(p3.id) = :ruleCount')
                            ->getDQL()
                    )
                );
                $qb->setParameter('rules', $rules)
                    ->setParameter('ruleCount', $rules->count());
            }
        }

        $searchCriteria = $this->buildSearchCriteria($searchCriteria);
        $qb->addCriteria($searchCriteria);

        return $qb;
    }

    public function buildUserFavouriteQuery(User $user, ?PropertyType $type, mixed $searchCriteria): QueryBuilder
    {
        $qb = $this->buildFindByCityAndRoomQuery(null, $type, $searchCriteria);
        $propertyIds = array_map(function (Favorite $favorite) {
            return $favorite->getProperty()->getId();
        }, $user->getFavorites()->toArray());
        
        $qb->andWhere('p.id IN (:propertyIds)');
        $qb->setParameter('propertyIds', $propertyIds);
        return $qb;
    }


    private function buildSearchCriteria(mixed $searchCriteria): Criteria
    {
        $expressions = [];
        $expressionBuilder = Criteria::expr();
        if ($priceMin = $searchCriteria['priceMin'] ?? null) {
            $expressions[] = $expressionBuilder->gte('p.price', $priceMin);
        }
        if ($priceMax = $searchCriteria['priceMax'] ?? null) {
            $expressions[] = $expressionBuilder->lte('p.price', $priceMax);
        }
        if ($squareMin = $searchCriteria['squareMin'] ?? null) {
            $expressions[] = $expressionBuilder->gte('p.square', $squareMin);
        }
        if ($squareMax = $searchCriteria['squareMax'] ?? null) {
            $expressions[] = $expressionBuilder->lte('p.square', $squareMax);
        }
        if ($numRooms = $searchCriteria['rooms'] ?? null) {
            $expressions[] = $this->buildMultiChoiceExpression($expressionBuilder, $numRooms, "p.numRooms");
        }
        if ($numBathrooms = $searchCriteria['bathrooms'] ?? null) {
            $expressions[] = $this->buildMultiChoiceExpression($expressionBuilder, $numBathrooms, "p.numBathrooms");
        }

        $expression = $expressionBuilder->andX(...$expressions);
        return new Criteria($expression);
    }

    private function buildMultiChoiceExpression(
        ExpressionBuilder $expressionBuilder,
        array $fieldDataArray,
        string $fieldName
    ): Expression {
        $intValues = array_filter($fieldDataArray, fn ($value) => is_int($value));
        $stringValues = array_filter($fieldDataArray, fn ($value) => !is_int($value));
        $stringExpressions = array_map(fn ($stringCompare) => $this->buildStringCriteria($expressionBuilder, $fieldName, $stringCompare), $stringValues);
        return $expressionBuilder->orX(
            $expressionBuilder->in($fieldName, $intValues),
            ...$stringExpressions
        );
    }

    /**
     * build and criteria for strings like ">3", ">=3", "<3", "<=2"
     */
    private function buildStringCriteria(ExpressionBuilder $expressionBuilder, string $field, string $stringCompare)
    {
        list($operator, $value) = $this->splitOperatorValue($stringCompare);

        switch ($operator) {
            case '>':
                return $expressionBuilder->gt($field, $value);
            case '<':
                return $expressionBuilder->lt($field, $value);
            case '>=':
                return $expressionBuilder->gte($field, $value);
            case '<=':
                return $expressionBuilder->lte($field, $value);
            default:
                throw new Exception(sprintf("not supported comparator %s, original string passed %s", $operator, $stringCompare));
        }
    }

    private function splitOperatorValue($string)
    {
        // Use regular expressions to match the operator and value
        preg_match('/(<=|>=|<|>|==|!=|=)(\d+)/', $string, $matches);
        // Return an array with the operator and the value
        return [$matches[1], $matches[2]];
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
