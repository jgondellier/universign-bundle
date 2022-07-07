<?php

namespace Gondellier\UniversignBundle\Service;

use Gondellier\UniversignBundle\Classes\TransactionRequest;
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
    public function __construct(string $uri)
    {
        $this->uri = $uri;
        $this->client = new Client([
            'base_uri'  => '',
            'timeout'   => 200.0,
            'verify'    => false,
        ]);
    }

    /**
     * Requests a new transaction for the client signature service. Sends the document to be signed and other parameters
     * and returns an URL where the end user should be redirected to. A transaction must be completed whithin 14 days
     * after its request.
     *
     * @param TransactionRequest $transactionRequest La transaction complete en xmlrpc
     * @return void
     */
    public function validate(TransactionRequest $transactionRequest)
    {
        $response = $this->client->request('POST', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.requestTransaction',$transactionRequest->getArray())
        ]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());
        $this->fault = $transactionRequest->checkResponseFault($this->originalResult);
    }

    /**
     * Refreshes the creation date for the transaction. The invitation email is sent again if the parameters allow it
     * (chainingMode equals email and in the case of the first signer, mustContactFirstSigner equals true).
     * This method can be used to postpone the expiration date of the transaction.
     *
     * @param String $transactionId l'id de de transction
     * @return void
     */
    public function cancelTransaction(String $transactionId)
    {
        $response = $this->client->request('POST', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.cancelTransaction',$transactionId)
        ]);

        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());

        if (is_array($this->originalResult) && xmlrpc_is_fault($this->originalResult)) {
            $this->fault = $this->originalResult;
        }
    }

    /**
     * Cancel a transaction in progress with this id.
     *
     * @param String $transactionId
     * @return void
     */
    public function relaunchTransaction(String $transactionId)
    {
        $response = $this->client->request('POST', $this->uri.'/sign/rpc/', [
            'body' => xmlrpc_encode_request('requester.relaunchTransaction',$transactionId)
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
