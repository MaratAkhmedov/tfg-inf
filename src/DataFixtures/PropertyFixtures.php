<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Photo;
use App\Entity\Property;
use App\Repository\CityRepository;
use App\Repository\PropertyTypeRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private CityRepository $cityRepository,
        private UserRepository $userRepository,
        private PropertyTypeRepository $propertyTypeRepository
    ){}

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $property = new Property();
            $property->setName('Propiedad '.$i.' de pruebas');
            $property->setDescription("Es la descripción de la vivienda de pruebas, solo se usa en entorno pre");
            $property->setType($this->propertyTypeRepository->findOneBy(['name' => 'room']));

            $photo = new Photo();
            $photo->setUrl("images/placeholder400x200.png");

            $property->addPhoto($photo);
            $property->setAddress($this->generateRandomAddress($i));
            $property->setUser($this->userRepository->findOneBy(['email' => 'owner@test.com']));

            $manager->persist($property);
        }

        $manager->flush();    
    }

    private function generateRandomAddress($pos): Address
    {
        $address = new Address();
        $address->setPostalCode('46009');
        $address->setStreet('Carrer de Vilanova de Castelló'.$pos);
        $address->setLatitude(39.4887556);
        $address->setLongitude(-0.3784859);
        $address->setFormattedAddress('C/ de Vilanova de Castelló, '.$pos.', La Saïdia, 46009 València, Valencia, Spain');
        $address->setPlaceId('ChIJ43qd3v5FYA0R8c_pvy3tmMY');
        $address->setCity($this->cityRepository->find(1));
        return $address;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}