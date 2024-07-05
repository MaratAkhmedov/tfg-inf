<?php

namespace App\Dto;

class AddressDTO
{
    private ?string $street = null; //Route
    private ?string $city = null;   //Locality
    private ?string $province = null;   //administrativeAreaLevel2
    private ?string $autoconomousComunity = null;   //administrativeAreaLevel1
    private ?string $postalCode = null;
    private ?string $formattedAddress = null;
    private ?string $country = null;
    private ?string $placeId = null;

    private ?float $longitude = null;
    private ?float $latitude = null;

    /**
     * Get the value of street
     */ 
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     *
     * @return  self
     */ 
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of province
     */ 
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set the value of province
     *
     * @return  self
     */ 
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get the value of autoconomousComunity
     */ 
    public function getAutoconomousComunity()
    {
        return $this->autoconomousComunity;
    }

    /**
     * Set the value of autoconomousComunity
     *
     * @return  self
     */ 
    public function setAutoconomousComunity($autoconomousComunity)
    {
        $this->autoconomousComunity = $autoconomousComunity;

        return $this;
    }

    /**
     * Get the value of postalCode
     */ 
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set the value of postalCode
     *
     * @return  self
     */ 
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get the value of formattedAddress
     */ 
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * Set the value of formattedAddress
     *
     * @return  self
     */ 
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    /**
     * Get the value of longitude
     */ 
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of longitude
     *
     * @return  self
     */ 
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the value of latitude
     */ 
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the value of latitude
     *
     * @return  self
     */ 
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the value of country
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */ 
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of placeId
     */ 
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Set the value of placeId
     *
     * @return  self
     */ 
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;

        return $this;
    }
}
