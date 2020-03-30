<?php

namespace Gondellier\UniversignBundle\Util;

use Gondellier\UniversignBundle\Classes\Request\FaultResponse;

class RequestTool
{
    /**
     * Regarde dans la requete s'il y a une erreur.
     *
     * @param $originalResult
     * @return FaultResponse|null
     */
    public function checkResponseFault($originalResult): ?FaultResponse
    {
        if(isset($originalResult['faultCode'])) {
            $fault = new FaultResponse();
            $fault->setFaultCode($originalResult['faultCode']);
            $fault->setFaultString($originalResult['faultString']);
            return $fault;
        }
        return null;
    }
}