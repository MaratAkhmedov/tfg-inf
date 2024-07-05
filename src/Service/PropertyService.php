<?php

namespace App\Service;

use App\Dto\AddressDTO;
use App\Entity\Address;
use App\Entity\Property;
use App\Repository\AddressRepository;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;

class PropertyService implements IPropertyService
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private AddressRepository $addressRepository,
        private IMapsService $mapsService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function generateProperty(Property $property, ?string $placeId): Property
    {
        if(!empty($placeId)) {
            $address = $this->addressRepository->getAddressFromDTO(
                $this->mapsService->getAddressFromPlaceId($placeId)
            );
            $property->setAddress($address);
        }
        
        $this->entityManager->persist($property);
        $this->entityManager->flush();

        return $property;
    }
}
