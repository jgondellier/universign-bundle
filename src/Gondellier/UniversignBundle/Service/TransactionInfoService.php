<?php

namespace Gondellier\UniversignBundle\Service;

use GuzzleHttp\Client;

class TransactionInfoService
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
            'base_uri' => '',
            'timeout' => 200.0,
            'verify' => false,
        ]);
    }

    public function getTransactionInfo($transactionID): void
    {
        $response = $this->client->request('GET', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.getTransactionInfo',$transactionID)
        ]);

        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());
        if (is_array($this->originalResult) && xmlrpc_is_fault($this->originalResult)) {
            $this->fault = $this->originalResult;
        }
    }

    public function getTransactionInfoByCustomID($transactionID): void
    {
        $response = $this->client->request('GET', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.getTransactionInfoByCustomId',$transactionID)
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