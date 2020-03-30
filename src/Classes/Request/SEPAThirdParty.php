<?php

namespace Gondellier\UniversignBundle\Classes\Request;

class SEPAThirdParty extends Base
{
    /**
     * The full name of this debtor/creditor
     */
    public $name;
    /**
     * The address of this debtor/creditor.
     */
    public $address;
    /**
     * The postal code of this debtor/creditor.
     */
    public $postalCode;
    /**
     * The city of this debtor/creditor.
     */
    public $city;
    /**
     * The country of this debtor/creditor.
     */
    public $country;

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }


}