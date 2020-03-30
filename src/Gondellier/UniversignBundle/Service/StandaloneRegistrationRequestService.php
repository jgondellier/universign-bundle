<?php

namespace Gondellier\UniversignBundle\Service;

use Gondellier\UniversignBundle\Classes\Request\StandaloneRegistration;
use GuzzleHttp\Client;

class StandaloneRegistrationRequestService{
    private $uri;
    public $client;
    private $originalResult;
    public $fault;

    /**
     * ValidationRequest constructor.
     * @param string $uri url d'appel du service
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->client = new Client([
            'base_uri'  => '',
            'timeout'   => 200.0,
            'verify'    => false,
        ]);
    }
    public function validate(StandaloneRegistration $StandaloneRegistration)
    {
        $response = $this->client->request('POST', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.requestRegistration',$StandaloneRegistration->getArray())
        ]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());
        $this->fault = $StandaloneRegistration->checkResponseFault($this->originalResult);

    }
    /**
     * @return mixed
     */
    public function getOriginalResult()
    {
        return $this->originalResult;
    }
}