<?php

namespace Gondellier\UniversignBundle\Classes;

class FaultResponse
{
    private $faultCode;
    private $faultString;

    /**
     * @return mixed
     */
    public function getFaultCode()
    {
        return $this->faultCode;
    }

    /**
     * @param mixed $faultCode
     */
    public function setFaultCode($faultCode): void
    {
        $this->faultCode = $faultCode;
    }

    /**
     * @return mixed
     */
    public function getFaultString()
    {
        return $this->faultString;
    }

    /**
     * @param mixed $faultString
     */
    public function setFaultString($faultString): void
    {
        $this->faultString = $faultString;
    }

}