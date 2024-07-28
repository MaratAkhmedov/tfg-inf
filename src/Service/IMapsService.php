<?php

namespace App\Service;

use App\Dto\AddressDto;

interface IMapsService
{
    function getAddressFromPlaceId(string $placeId) : AddressDto;
}
