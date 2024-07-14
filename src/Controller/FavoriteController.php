<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Property;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SearchPropertyType;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favorite')]
class FavoriteController extends AbstractController
{
    #[Route('/add/{property}', name: 'app_add_favorite', methods: ['PUT'], options: ["expose" => true])]
    public function add(Property $property, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse(['redirect' => $this->generateUrl('app_login')], 401);
        }
        $user = $this->getUser();
        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setProperty($property);
        $entityManager->persist($favorite);
        $entityManager->flush();
        return new JsonResponse([]);
    }

    #[Route('/remove/{property}', name: 'app_remove_favorite', methods: ['DELETE'], options: ["expose" => true])]
    public function remove(Property $property, EntityManagerInterface $entityManager, FavoriteRepository $favoriteRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse(['redirect' => $this->generateUrl('app_login')], 401);
        }
        $user = $this->getUser();
        $favorite = $favoriteRepository->findOneBy(['property' => $property, 'user' => $user]);
        $entityManager->remove($favorite);
        $entityManager->flush();
        return new JsonResponse([]);
    }

    #[Route('/search', name: 'app_search_favorite', methods: ['GET', 'POST'], options: ["expose" => true])]
    #[IsGranted('ROLE_USER')]
    public function searchFavorites(
        Request $request,
        PropertyRepository $propertyRepository,
        UserRepository $userRepository,
        PaginatorInterface $paginator
    ): Response {
        $type = null;
        $searchData = [];
        $searchForm = $this->createForm(SearchPropertyType::class, null);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            if($data['type'] ?? null) {
                $type = $data['type'];
            }
            $searchData = $data;
        }
        $builder = $propertyRepository->buildUserFavouriteQuery(
            $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]),
            $type,
            $searchData
        );
        $paginator = $paginator->paginate(
            $builder,
            $request->query->getInt('page', 1),
            12
        );

        $coordinates = array_map(
            fn (Property $item) => [
                'lat' => $item->getAddress()->getLatitude(),
                'lng' => $item->getAddress()->getLongitude(),
                'id' => $item->getId()
            ],
            (array)$paginator->getItems()
        );

        return $this->render('public/search.html.twig', [
            'searchForm' => $searchForm->createView(),
            'pagination' => $paginator,
            'coordinates' => $coordinates
        ]);
    }
}
