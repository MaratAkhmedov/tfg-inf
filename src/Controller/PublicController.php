<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PropertyTypeRepository;
use App\Entity\City;
use App\Entity\Property;
use App\Entity\PropertyType;
use App\Form\SearchPropertyType;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class PublicController extends AbstractController
{
    public function __construct(
        private CityRepository $cityRepository,
        private PropertyTypeRepository $propertyTypeRepository
    ) {
    }

    #[Route('/', 'default')]
    #[Template('public/index.html.twig')]
    public function index(): array
    {
        return [
            "propertyTypes" => $this->propertyTypeRepository->findAll(),
            "cities" => $this->cityRepository->findAll()
        ];
    }

    #[Route('/contact', 'contact')]
    #[Template('public/contact.html.twig')]
    public function contact(): array
    {
        return [];
    }

    #[Route('/about', 'about_us')]
    #[Template('public/about.html.twig')]
    public function about(): array
    {
        return [];
    }

    #[Route('property/{id}', name: 'app_property_show', methods: ['GET'], requirements: ['id' => '\d+'], options: ["expose" => true])]
    public function show(Property $property): Response
    {
        // TODO: check if can see the property
        return $this->render('public/property.html.twig', [
            'property' => $property,
        ]);
    }

    #[Route('search/{type}/{city}', name: 'app_search_property_type_city', methods: ['GET', 'POST'], options: ["expose" => true])]
    public function search(
        Request $request,
        PropertyType $type,
        City $city,
        PropertyRepository $propertyRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchData = [];
        $searchForm = $this->createForm(SearchPropertyType::class, null, [
            'currentPropertyType' => $type
        ]);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            if ($data['type']->getId() != $type->getId()) {
                return $this->redirectToRoute('app_search_property_type_city', [
                    'type' => $data['type']->getId(),
                    'city' => $city->getId()
                ]);
            }
            $searchData = $data;
        }
        $builder = $propertyRepository->buildFindByCityAndRoomQuery($city, $type, $searchData);
        $paginator = $paginator->paginate(
            $builder, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
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

    #[Route('search/favorites', name: 'app_search_favorites', methods: ['GET', 'POST'], options: ["expose" => true])]
    public function searchFavorites(
        Request $request,
        PropertyRepository $propertyRepository,
        UserRepository $userRepository,
        PaginatorInterface $paginator
    ): Response {
        $type = $this->propertyTypeRepository->findOneBy(['name' => 'room']);
        $searchData = [];
        $searchForm = $this->createForm(SearchPropertyType::class, null, [
            'currentPropertyType' => $type
        ]);
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
