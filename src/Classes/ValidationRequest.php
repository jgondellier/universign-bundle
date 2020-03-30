<?php

namespace Gondellier\UniversignBundle\Classes;

class ValidationRequest extends Base
{
    public $idDocument;
    public $personalInfo;
    public $allowManual;
    public $callbackURL;

    /**
     * @return array
     */
    public function getIdDocument(): array
    {
        return $this->idDocument;
    }

    /**
     * @param IdDocument $idDocument
     */
    public function setIdDocument(IdDocument $idDocument): void
    {
        //Check if document type match with the document number
        $idDocument->verifyTypeWithPhoto();
        $this->idDocument = $idDocument->getArray();
    }

    /**
     * @return array
     */
    public function getPersonalInfo(): array
    {
        return $this->personalInfo;
    }

    /**
     * @param PersonalInfo $personalInfo
     */
    public function setPersonalInfo(PersonalInfo $personalInfo): void
    {

        $this->personalInfo = $personalInfo->getArray();
    }

    /**
     * @return bool
     */
    public function getAllowManual():bool
    {
        return $this->allowManual;
    }

    /**
     * Si paramètre a False alors la validation ne se fera que par un robot sans faire appel a un humain
     *
     * @param bool $allowManual
     */
    public function setAllowManual(bool $allowManual): void
    {
        $this->allowManual = $allowManual;
    }

    /**
     * @return string
     */
    public function getCallbackURL():string
    {
        return $this->callbackURL;
    }

    /**
     * @param string $callbackURL
     */
    public function setCallbackURL(string $callbackURL): void
    {
        $this->callbackURL = $callbackURL;
    }


}