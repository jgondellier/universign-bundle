<?php

namespace Gondellier\UniversignBundle\Service;

use Gondellier\UniversignBundle\Classes\MatchAccount;
use GuzzleHttp\Client;

class MatchAccountRequestService
{
    private const OBFS='*';
    private $bestResult;
    private $account;
    private $partialAccount;
    private $isCertified;
    private $explanation;
    public $uri;
    public $client;
    public $originalResult;
    public $fault;

    /**
     * MatchAccount constructor.
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
     * Initialise la recherche...
     * @param MatchAccount $matchAccount
     */
    public function match(MatchAccount $matchAccount):void
    {
        $response = $this->client->request('GET', $this->uri.'/ra/rpc/', ['body' => xmlrpc_encode_request('matcher.matchAccount',$matchAccount->getArray())]);
        $this->originalResult = xmlrpc_decode($response->getBody()->getContents());

        $this->fault = $matchAccount->checkResponseFault($this->originalResult);

        if(empty($this->fault)){
            $this->findBestResult();
            $this->explain();
            //$this->bestResult = $this->getBestResult();
        }
    }

    /**
     *  Donne le compte trouvé s'il y a un seul résultat et que ca correspond
     *
     * @return bool|array
     */
    private function findAccount()
    {
        if(!empty($this->originalResult) && (count($this->originalResult) === 1) && !in_array(self::OBFS, $this->originalResult, true)) {
            $this->isCertified=$this->isCertified($this->originalResult[0]);
            return  $this->account = $this->originalResult[0];
        }
        return False;
    }

    /**
     * Universign retourne une liste de compte trié par ordre alaphabetique sur les nom et prénom des comptes trouvés.
     * L'unicité d'un compte aujourd'hui est l'email et le telephone.
     * On recherche dans la liste de résultat les plus pertinents.
     */
    private function findBestResult(): void
    {
        if(!$this->findAccount()){
            $bestResult = array();
            if(empty($this->originalResult)){
                return;
            }

            foreach($this->originalResult as $result){
                //On a trouvé l'email
                if(array_key_exists('email',$result) && strpos($result['email'],self::OBFS)===False){
                    $bestResult['findEmail']=$result;
                    if(array_key_exists('findMobile',$bestResult)){
                        break;
                    }
                }
                //On a trouvé le mobile
                if(array_key_exists('mobile',$result) && strpos($result['mobile'],self::OBFS)===False){
                    $bestResult['findMobile']=$result;
                    if(array_key_exists('findEmail',$bestResult)){
                        break;
                    }
                }
            }
            //On a trouvé le mail et le mobile dans le meme resultat on a un compte mais PB de prenom et nom
            if(array_key_exists('findEmail',$bestResult) && array_key_exists('findMobile',$bestResult)){
                if($bestResult['findEmail']===$bestResult['findMobile']){
                    $this->isCertified=$this->isCertified($bestResult['findEmail']);
                    $this->partialAccount=$bestResult['findEmail'];
                    return;
                }else{
                    $this->bestResult = $bestResult;
                }
            }elseif(array_key_exists('findEmail',$bestResult)){
                $this->bestResult = $bestResult;
            }elseif(array_key_exists('findMobile',$bestResult)){
                $this->bestResult = $bestResult;
            }
        }
    }

    /**
     * Retourne le résultat le plus pertinent.
     *
     * @return array|bool
     */
    public function getBestResult()
    {
        if($this->account){
            return $this->account;
        }
        if($this->partialAccount){
            return $this->partialAccount;
        }
        return $this->bestResult;
    }

    /**
     * Est ce qu'un certificat a été trouvé ?
     *
     * @param array $account
     * @return bool
     */
    private function isCertified(array $account): bool
    {
        return array_key_exists('certificateLevel', $account) && $account['certificateLevel'] === 'certified';
    }

    /**
     * Permet de savoir en fonction du niveau de signature que l'on souhaite si le compte peut signé en l'état.
     *
     * @param int $neededSignLevel Niveau de la signature souhaité
     * @return bool
     */
    public function isValid($neededSignLevel): bool
    {
        switch ($neededSignLevel) {
            case 1:
                //Compte trouvé ou compte trouvé sans prenom et nom mais sans certificat
                if(($this->partialAccount && $this->isCertified===False) || $this->account ){
                    return true;
                }
                break;
            case 2:
                //Compte trouvé et avec certificat
                if($this->account && $this->isCertified){
                    return true;
                }
                break;
        }
        return False;
    }

    /**
     * Trouve une expliquation au résultat trouvé.
     */
    public function explain(): void
    {
        if($this->account && $this->isCertified){
            $this->explanation = 'Aucun problème pour signer n\'importe quelle acte.';
            return;
        }
        if($this->account && !$this->isCertified){
            $this->explanation = 'Attention compte de niveau 1. La CNI pourra être demandée.';
            return;
        }
        if($this->partialAccount && $this->isCertified){
            $explain = 'Compte de niveau 2 mais ';
            if(strpos($this->partialAccount['lastname'],self::OBFS)!==False){
                $explain .= 'le nom ne correspond pas au client ';
            }
            if(strpos($this->partialAccount['firstname'],self::OBFS)!==False){
                $explain .= 'le prénom ne correspond pas au client ';
            }
            $explain .= '. Impossible de signer en niveau 2.';
            $this->explanation = $explain;
            return;
        }
        if($this->partialAccount && !$this->isCertified){
            $this->explanation = 'Compte de niveau 1. Le nom ou le prenom ne correspond pas. Pas de problème pour signer en niveau 1.
            Upload d\'une carte d\'identité pour signer en niveau2';
            return;
        }
        if(is_array($this->bestResult)){
            if(array_key_exists('findEmail',$this->bestResult) && array_key_exists('findMobile',$this->bestResult)){
                $this->explanation = 'Deux comptes trouvés. Un avec l\'email et un autre avec le téléphone. L\'association email et mobile doit être unique.';
                return;
            }
            if(array_key_exists('findEmail',$this->bestResult)){
                $this->explanation = 'L\'email demandé a été trouvé mais pas le téléphone.';
                return;
            }
            if(array_key_exists('findMobile',$this->bestResult)){
                $this->explanation = 'Le mobile demandé a été trouvé mais pas l\'email.';
                return;
            }
        }
        if($this->account===null && $this->partialAccount===null && $this->bestResult===null){
            $this->explanation = 'Aucun compte trouvé.';
            return;
        }
    }

    /**
     * @return mixed
     */
    public function getExplanation()
    {
        return $this->explanation;
    }
    /**
     * @return array
     */
    public function getOriginalResult():array
    {
        return $this->originalResult;
    }
}