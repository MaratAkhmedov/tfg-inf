<?php

namespace App\Repository;

use App\Dto\AddressDTO;
use App\Entity\Address;
use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private CityRepository $cityRepository
    ) {
        parent::__construct($registry, Address::class);
    }

    public function getAddressFromDTO(AddressDTO $addressDTO)
    {
        $address = new Address;
        $address->setPostalCode($addressDTO->getPostalCode());
        $address->setStreet($addressDTO->getStreet());
        $address->setLatitude($addressDTO->getLatitude());
        $address->setLongitude($addressDTO->getLongitude());
        $address->setFormattedAddress($addressDTO->getFormattedAddress());
        $address->setPlaceId($addressDTO->getPlaceId());

        if($addressDTO->getCity()) {
            $address->setCity(
                $this->cityRepository->getOrCreateCity($addressDTO->getCity(), $addressDTO->getProvince())
            );
        }

        return $address;
    }

    //    /**
    //     * @return Address[] Returns an array of Address objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Address
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
