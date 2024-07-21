<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin user
        $user = new User();
        $user->setEmail('admin@test.com');

        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();

        // Owner user
        $user = new User();
        $user->setEmail('owner@test.com');
        $user->setRoles(['ROLE_OWNER']);
        $user->setPassword($password);

        $password = $this->hasher->hashPassword($user, 'owner');
        $owner = new Owner();
        $owner->setFirstName("Jorge");
        $owner->setLastName("Sánchez Torres");
        $owner->setPhone("+34 727 734 395");
        $owner->setDni("Y4241051Y");
        $owner->setDescription("It is a description");
        $user->setOwnerData($owner);

        $manager->persist($user);
        $manager->flush();

        // Renter user
        $user = new User();
        $user->setEmail('renter@test.com');
        $user->setRoles($user->getRoles());

        $password = $this->hasher->hashPassword($user, 'renter');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
