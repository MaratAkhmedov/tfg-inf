<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private ProvinceRepository $provinceRepository
    ) {
        parent::__construct($registry, City::class);
    }

    public function getOrCreateCity(string $cityName, ?string $provinceCode): City
    {
        $city = $this->findOneBy(['name' => $cityName]);
        if ($city) {
            return $city;
        } else {
            $city = new City();
            $city->setName($cityName);
            if($provinceCode) {
                $city->setProvince($this->provinceRepository->findOneBy(['isoCode' => $provinceCode]));
            }
        }
        $this->getEntityManager()->persist($city);
        $this->getEntityManager()->flush();
        return $city;
    }

    //    /**
    //     * @return City[] Returns an array of City objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?City
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
