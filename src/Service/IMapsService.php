<?php

namespace App\Service;

use App\Dto\AddressDto;
use App\Entity\Property;

interface IMapsService
{
    function getAddressFromPlaceId(string $placeId) : AddressDto;
}
