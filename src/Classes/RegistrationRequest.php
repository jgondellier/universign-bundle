<?php

namespace Gondellier\UniversignBundle\Classes;

class RegistrationRequest extends Base
{
    private const TYPE_ID_CARD_FR       = 'id_card_fr';
    private const TYPE_PASSPORT_EU      = 'passport_eu';
    private const TYPE_TITRE_SEJOUR     = 'titre_sejour';
    private const TYPE_DRIVE_LICENSE    = 'drive_license';

    /**
     * List of ID documents to use to register the signer. The number of these documents is indicated in the following comment.
     */
    public $documents = array();
    /**
     * The type of the provided ID documents.
     * id_card_fr : French ID card. Two ID documents should be provided.
     * passport_eu : French Passport. Only one ID document should be provided.
     * titre_sejour : Residence Permit. Two ID documents should be provided.
     */
    public $type;

    /**
     * Verify if the number of doc is the same as expected.
     *
     * @return bool
     */
    public function verifyTypeWithDocuments():bool
    {
        //Controle if 2 docs are upload
        if($this->type === self::TYPE_ID_CARD_FR || $this->type === self::TYPE_TITRE_SEJOUR){
            if (count($this->documents) !== 2){
                return False;
            }
        }else if (count($this->documents) !== 1){
            return False;
        }
        return True;
    }

    /**
     * @return array
     */
    public function getDocuments():array
    {
        return $this->documents;
    }

    /**
     * @param array $documents
     */
    public function setDocuments(array $documents): void
    {
        $this->documents = $documents;
    }

    public function addDocuments(string $documentsPath):void
    {
        $documentsContent  = file_get_contents($documentsPath);
        xmlrpc_set_type($documentsContent,'base64');
        $this->documents[] = $documentsContent;
    }

    /**
     * @return string
     */
    public function getType():string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->checkValue('Type',$type,array(self::TYPE_ID_CARD_FR,self::TYPE_PASSPORT_EU,self::TYPE_TITRE_SEJOUR,self::TYPE_DRIVE_LICENSE));
        $this->type = $type;
    }
}