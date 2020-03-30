<?php

namespace Gondellier\UniversignBundle\Service;

use Gondellier\UniversignBundle\Classes\Request\ValidationRequest;
use GuzzleHttp\Client;

class ValidationRequestService{
    private $uri;
    private $originalResult;
    private $result;
    private $reason;
    private $id;
    private $status;
    private $explanation;
    public $client;
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

    public function validate(ValidationRequest $validationRequest): void
    {
        $response = $this->client->request('POST', $this->uri.'/ra/rpc/', [
            'body' => xmlrpc_encode_request('validator.validate',$validationRequest->getArray())
        ]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());

        $this->fault = $validationRequest->checkResponseFault($this->originalResult);

        if(empty($this->fault)){
            $this->result = $this->originalResult['result'];
            $this->reason = $this->originalResult['reason'];
            $this->id = $this->originalResult['id'];
            $this->status = $this->originalResult['status'];
            $this->traitValidationResult();
        }
    }
    /**
     * Permet d'interpréter la réponse qu'universign a fait.
     *
     */
    private function traitValidationResult():void
    {
        switch ($this->status) {
            case 0:
                $this->explanation[]='En cours';
                break;
            case 1:
                $this->explanation[]='Validé';
                break;
            case 2:
                $this->explanation[]='Refusé';
                switch ($this->reason) {
                    case 4:
                        $this->explanation[]='Lecture impossible de la pièce. Mauvaise qualité ?';
                        break;
                }
                if(is_array($this->result)){
                    foreach ($this->result as $field => $result){
                        if($result['valid']===false){
                            switch ($field) {
                                case 'firstname':
                                    $this->explanation[]='Le prénom du client ( '.$result['expected'].' ) ne correspond pas a celui trouvé sur la carte : '.$result['found'];
                                    break;
                                case 'lastname':
                                    $this->explanation[]='Le nom du client ( '.$result['expected'].' ) ne correspond pas a celui trouvé sur la carte : '.$result['found'];
                                    break;
                                case 'birthdate':
                                    $this->explanation[]='La date de naissance du client ( '.$result['expected'].' ) ne correspond pas a celle trouvée sur la carte : '.$result['found'];
                                    break;
                                default:
                                    $this->explanation[]='Le champs : '.$field.' on recherche : '.$result['expected'].' mais on a trouvé : '.$result['found'];
                                    break;
                            }
                        }
                    }
                }
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getOriginalResult()
    {
        return $this->originalResult;
    }

    /**
     * @return mixed
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * @return string l'id retourné par universign
     */
    public function getId():string
    {
        return $this->id;
    }
}