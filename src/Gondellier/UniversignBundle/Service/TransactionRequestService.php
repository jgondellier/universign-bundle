<?php

namespace Gondellier\UniversignBundle\Service;

use Gondellier\UniversignBundle\Classes\Request\TransactionRequest;
use GuzzleHttp\Client;

class TransactionRequestService
{
    private $uri;
    public $client;
    private $originalResult;
    public $fault;

    /**
     * TransactionRequestService constructor.
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

    public function validate(TransactionRequest $transactionRequest)
    {
        //file_put_contents('RPC_DEBUG_TransactionRequest.xml', xmlrpc_encode_request('requester.requestTransaction',$transactionRequest->getArray()));
        $response = $this->client->request('POST', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.requestTransaction',$transactionRequest->getArray())
        ]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());
        $this->fault = $transactionRequest->checkResponseFault($this->originalResult);
    }

    /**
     * @return mixed
     */
    public function getOriginalResult()
    {
        return $this->originalResult;
    }
}
