<?php

namespace App\Service;

use App\Entity\Property;

interface IPropertyService
{
    function generateProperty(Property $property, string $placeId) : Property;
}
