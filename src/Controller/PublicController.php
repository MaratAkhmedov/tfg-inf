<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PropertyTypeRepository;
use App\Entity\City;
use App\Entity\Property;
use App\Entity\PropertyType;
use App\Form\SearchPropertyType;
use App\Repository\PropertyRepository;
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

    #[Route('/phpinfo', 'phpinfo')]
    public function phpinfo():Response
    {
        return new Response(phpinfo());
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

    #[Route('search/{city}/{type?}', name: 'app_search_property_type_city', methods: ['GET', 'POST'], requirements: ["city" => "\d+"], options: ["expose" => true])]
    public function search(
        Request $request,
        City $city,
        ?PropertyType $type = null,
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

            if ($this->checkDifferentType($data['type'], $type)) {
                return $this->redirectToRoute('app_search_property_type_city', [
                    'type' => $data['type'] ? $data['type']->getId() : null,
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

    private function checkDifferentType(?PropertyType $submitType, ?PropertyType $requestType){
        if (isset($submitType) && isset($requestType)) {
            return $submitType->getId() !== $requestType->getId();
        } elseif (isset($submitType) != isset($requestType)) {
            return true;
        }
    }
}
