<?php

namespace Gondellier\UniversignBundle\Service;

use GuzzleHttp\Client;

class GetDocumentsService
{
    private $uri;
    public $client;
    private $originalResult;
    public $fault;
    public $file;

    /**
     * GetDocumentsService constructor.
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

    public function getDocuments($transactionID): void
    {
        $response = $this->client->request('GET', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.getDocuments',$transactionID)
        ]);

        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());
        if (is_array($this->originalResult) && xmlrpc_is_fault($this->originalResult)) {
            $this->fault = $this->originalResult;
        }
    }

    public function getDocumentsByCustomID($transactionID): void
    {
        $response = $this->client->request('GET', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.getDocumentsByCustomId',$transactionID)
        ]);

        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());
        if (is_array($this->originalResult) && xmlrpc_is_fault($this->originalResult)) {
            $this->fault = $this->originalResult;
        }
    }

    /**
     * @return mixed
     */
    public function getOriginalResult()
    {
        return $this->originalResult;
    }
}