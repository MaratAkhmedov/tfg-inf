<?php

namespace App\Service;

use App\Dto\AddressDTO;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleMapsService implements IMapsService
{
    private string $baseUrl;
    private string $apiKey;
    
    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $params
    ) {
        $this->baseUrl = $params->get('google_api')['base_url'];
        $this->apiKey = $params->get('google_api')['api_key'];
    }

    public function getAddressFromPlaceId(string $placeId) : AddressDTO
    {
        $result = $this->fetchPlaceDetails($placeId);

        return $this->buildPlaceAddress($result['result']);
    }

    private function buildPlaceAddress(array $result) : AddressDTO
    {
        $address = new AddressDTO();
        $addressComponents = $result['address_components'];
        foreach ($addressComponents as $addressComponent) {
            if(in_array('route', $addressComponent['types'])) {
                $address->setStreet($addressComponent['long_name']);
            }else if(in_array('locality', $addressComponent['types'])) {
                $address->setCity($addressComponent['short_name']);
            }else if(in_array('administrative_area_level_2', $addressComponent['types'])) {
                $address->setProvince($addressComponent['short_name']);
            }else if(in_array('administrative_area_level_1', $addressComponent['types'])) {
                $address->setAutoconomousComunity($addressComponent['short_name']);
            }else if(in_array('country', $addressComponent['types'])) {
                $address->setCountry($addressComponent['short_name']);
            }else if(in_array('postal_code', $addressComponent['types'])) {
                $address->setPostalCode($addressComponent['long_name']);
            }
        }

        $address->setLatitude($result['geometry']['location']['lat']);
        $address->setLongitude($result['geometry']['location']['lng']);
        $address->setFormattedAddress($result['formatted_address']);
        $address->setFormattedAddress($result['formatted_address']);
        $address->setPlaceId($result['place_id']);
        return $address; 
    }

    private function fetchPlaceDetails(string $placeId) {
        $response = $this->client->request(
            'GET',
            sprintf("%s/maps/api/place/details/json?key=%s&place_id=%s", $this->baseUrl, $this->apiKey, $placeId)
        );
        $content = json_decode($response->getContent(), true);

        return $content;
    }
}
