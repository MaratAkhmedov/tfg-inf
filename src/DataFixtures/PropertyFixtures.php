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
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private CityRepository $cityRepository,
        private UserRepository $userRepository,
        private PropertyTypeRepository $propertyTypeRepository,
        private KernelInterface $kernelInterface
    ){}

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 13; $i++) {
            $property = new Property();
            $property->setName('Propiedad '.$i.' de pruebas');
            $property->setShortDescription("Es la descripción corta de pruebas para la propiedad $i");
            $property->setDescription("Es la descripción larga de pruebas para la propiedad $i");
            $property->setType($this->propertyTypeRepository->findOneBy(['name' => 'room']));
            
            $photo1 = new Photo();
            $imageName1 = $this->findFilesWithPrefix("sample_property_" . $i . ".");
            $photo1->setUrl("images/$imageName1");
            $property->addPhoto($photo1);

            $photo2 = new Photo();
            $imageName2 = $this->findFilesWithPrefix("sample_property_" . $i+1 . ".");
            $photo2->setUrl("images/$imageName2");
            $property->addPhoto($photo2);

            $property->setAddress($this->generateRandomAddress($i));
            $property->setUser($this->userRepository->findOneBy(['email' => 'owner@test.com']));
            $property->setPrice(rand(100, 1000));
            $manager->persist($property);
        }

        $manager->flush();    
    }

    private function findFilesWithPrefix(string $prefix)
    {
        $finder = new Finder();
        $finder->files()->in($this->kernelInterface->getProjectDir() . "/assets/images/")->name($prefix . '*');

        foreach ($finder as $file) {
            return $file->getRelativePathname();
        }
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