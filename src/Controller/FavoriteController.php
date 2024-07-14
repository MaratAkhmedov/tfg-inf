<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Property;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FavoriteController extends AbstractController
{
    #[Route('/favorite/add/{property}', name: 'app_add_favorite', methods: ['PUT'], options: ["expose" => true])]
    public function add(Property $property, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setProperty($property);
        $entityManager->persist($favorite);
        $entityManager->flush();
        return new JsonResponse([]);
    }

    #[Route('/favorite/remove/{property}', name: 'app_remove_favorite', methods: ['DELETE'], options: ["expose" => true])]
    public function remove(Property $property, EntityManagerInterface $entityManager, FavoriteRepository $favoriteRepository): JsonResponse
    {
        $user = $this->getUser();
        $favorite = $favoriteRepository->findOneBy(['property' => $property, 'user' => $user]);
        $entityManager->remove($favorite);
        $entityManager->flush();
        return new JsonResponse([]);
    }
}
