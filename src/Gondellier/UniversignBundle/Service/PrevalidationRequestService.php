<?php

namespace Gondellier\UniversignBundle\Service;

use App\Gondellier\UniversignBundle\Classes\Request\PrevalidationRequest;
use Gondellier\UniversignBundle\Classes\Request\ErrorResponse;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PrevalidationRequestService
{
    private $uri;
    private $token;
    public $client;
    private $originalResult;
    public $fault;
    public $file;
    public $transferTime;

    /**
     * GetDocumentsService constructor.
     * @param string $uri url d'appel du service
     * @param ParameterBagInterface $params
     */
    public function __construct($uri,$token)
    {
        $this->uri = $uri;
        $this->token = $token;
    }

    public function prevalidation(PrevalidationRequest $preValidationRequest): void
    {
        $transfertTime =0;
        $client = new Client([
            'base_uri'  => '',
            'timeout'   => 200.0,
            'verify'    => false,
            'Content-Type' => 'application/json',
            'http_errors' => false,
            'debug' => false,
            'on_stats' => static function (TransferStats $stats) use (&$transfertTime){
                $transfertTime = $stats->getTransferTime();
            },
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept'        => 'application/json',
            ],
            'multipart' => $preValidationRequest->getRequestParams()
        ]);
        $this->transferTime = $transfertTime;

        $response = $client->request('POST', $this->uri);
        $this->originalResult = json_decode($response->getBody()->getContents(), true);
        if (is_array($this->originalResult) && (array_key_exists('error_description',$this->originalResult))) {
            $errorResponse = new ErrorResponse();
            if(array_key_exists('type',$this->originalResult)){
                $errorResponse->setType($this->originalResult['type']);
            }
            if(array_key_exists('error',$this->originalResult)){
                $errorResponse->setError($this->originalResult['error']);
            }
            if(array_key_exists('error_description',$this->originalResult)){
                $errorResponse->setParam($this->originalResult['error_description']);
            }
            if(array_key_exists('param',$this->originalResult)){
                $errorResponse->setErrorDescription($this->originalResult['param']);
            }
            $this->fault = $errorResponse;
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