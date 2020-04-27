<?php

namespace Gondellier\UniversignBundle\Service;

use Gondellier\UniversignBundle\Classes\ValidationRequest;
use GuzzleHttp\Client;

class ValidationRequestService{
    private $uri;
    private $originalResult;
    private $result;
    private $reason;
    private $reasonMessage;
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

    /**
     * Send Identity card to pre validate identity
     *
     * @param ValidationRequest $validationRequest
     */
    public function validate(ValidationRequest $validationRequest): void
    {
        $response = $this->client->request('POST', $this->uri.'/ra/rpc/', [
            'body' => xmlrpc_encode_request('validator.validate',$validationRequest->getArray())
        ]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());

        $this->fault = $validationRequest->checkResponseFault($this->originalResult);

        if(empty($this->fault)){
            if(array_key_exists('result',$this->originalResult)){
                $this->result = $this->originalResult['result'];
            }
            if(array_key_exists('reason',$this->originalResult)){
                $this->result = $this->originalResult['reason'];
            }
            if(array_key_exists('reasonMessage',$this->originalResult)){
                $this->result = $this->originalResult['reasonMessage'];
            }
            if(array_key_exists('id',$this->originalResult)){
                $this->result = $this->originalResult['id'];
            }
            if(array_key_exists('status',$this->originalResult)){
                $this->result = $this->originalResult['status'];
            }
            $this->traitValidationResult();
        }
    }

    /**
     * Get information about identidity pre validate card
     *
     * @param string $id
     */
    public function getResult(string $id): void
    {
        $response = $this->client->request('POST', $this->uri.'/ra/rpc/', [
            'body' => xmlrpc_encode_request('validator.getResult',$id)
        ]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());

        if (is_array($this->originalResult) && xmlrpc_is_fault($this->originalResult)) {
            $this->fault = $this->originalResult;
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
                $this->explanation['status']='pending validation';
                break;
            case 1:
                $this->explanation['status']='valid';
                break;
            case 2:
                $this->explanation['status']='invalid';
                if($this->reasonMessage){
                    switch($this->reasonMessage){
                        case 'AIDOS extraction for IDENTITY_CARD_FR: FAILURE: Client exception':
                        case 'AIDOS extraction for PASSPORT_EU: FAILURE: Client exception':
                        case 'TEXT_EXTRACTION_FAILURE':
                        case 'Mauvais cadrage, problème de reflet ou luminosité, image floue':
                            $this->explanation[]='Lecture impossible de la pièce. Impossible d\'extraire les informations.';
                            break;
                        case 'MRZ_CHECKSUM_FAILURE':
                            $this->explanation[]='Lecture impossible de la pièce. La piste MRZ est illisible.';
                        break;
                        case '[opticalLine1 too short, opticalLine2 too short, notAfter too short]':
                        case '[opticalLine1 too short, opticalLine2 too short]':
                        case '[opticalLine2 too long, The check digit for the birth date field is incorrect]':
                            $this->explanation[]='Le numéro d\'identification de la pièce n\'est pas valide.';
                            break;
                        case 'Wrong information':
                        case 'Information différentes entre la carte et l\'enregistrement':
                            $this->explanation[]='Les informations du client ne correspondent pas à ceux de la carte.';
                            break;
                        case '[card expired]':
                        case 'La date est expirée / Ancien format':
                            $this->explanation[]='La pièce d\'identité a expiré.';
                            break;
                        case 'La pièce donnée n\'est pas une pièce d\'identité':
                            $this->explanation[]='Le type de carte ne correspond pas a celle fourni.';
                            break;
                        default:
                            $this->explanation[]=$this->reasonMessage;
                    }
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