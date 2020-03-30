<?php

namespace Gondellier\UniversignBundle\Classes\Request;

class TransactionSigner extends Base
{
    private const ROLE_SIGNER                   = 'signer';
    private const ROLE_OBSERVER                 = 'observer';
    private const CERTIFICATE_TYPE_LOCAL        = 'local';
    private const CERTIFICATE_TYPE_SIMPLE       = 'simple';
    private const CERTIFICATE_TYPE_CERTIFIED    = 'certified';
    private const CERTIFICATE_TYPE_ADVANCED     = 'advanced';

    /**
     * This signer’s firstname. Note that this field is mandatory for a self-signed certificate.
     * When using validationSessionId, it must be set to the same value than the one used in the validation request.
     */
    public $firstname;
    /**
     * This signer’s lastname. Note that this field is mandatory for a self-signed certificate.
     * When using validationSessionId, it must be set to the same value than the one used in the validation request.
     */
    public $lastname;
    /**
     * This signer’s organization.
     */
    public $organization;
    /**
     * The name of the signer profile to use for some customizations. It is set up by the UNIVERSIGN team.
     */
    public $profile;
    /**
     * This signer’s e-mail address. Note that all users except the first must have an email address set. The first user must have one if
     * he has to be contacted by e-mail, e.g. for authentication or if the mustContactFirstSigner parameter of TransactionRequest is set to true.
     */
    public $emailAddress;
    /**
     * This signer’s mobile phone number that should be written in the international format: the country code followed by the phone
     * number (for example, in France 33 XXXXXXXXX).
     */
    public $phoneNum;
    /**
     * The language for the signer’s transaction. The valid values are:
     * bg for Bulgarian
     * ca for Catalan
     * de for German
     * en for English (default value)
     * es for Spanish
     * fr for French
     * it for Italian
     * nl for Dutch
     * pl for Polish
     * pt for Portuguese
     * ro for Romanian
     */
    public $language;
    /**
     * The role of this transaction actor
     * - signer (default) This actor is a signer and he will be able to view the documents and sign them.
     * - observer This actor is an observer and he will be able only to view the documents.
     */
    public $role;
    /**
     * This signer’s birth date. This is an option for the certified signature, if it’s set, the user won’t be asked to provide it’s birth date during the RA workflow.
     * When using validationSessionId, it must be set to the same value than the one used in the validation request.
     */
    public $birthDate;
    /**
     * An external identifier given by the organization that indicates this signer.
     */
    public $universignId;
    /**
     * The url to where the signer will be redirected, after the signatures are completed. If it is null, it takes the default Universign success URL.
     * Length is limited to 512 characters.
     */
    public $successRedirection;
    /**
     * The url to where the signer will be redirected, after the signatures are canceled. If it is null, it takes the value of successURL.
     * If it is also null, it takes the default Universign cancel URL.
     * Length is limited to 512 characters.
     */
    public $cancelRedirection;
    /**
     * The url to where the signer will be redirected, after the signatures are failed. If it is null, it takes the value of cancelURL.
     * If it is also null, it takes the default Universign failure URL.
     * Length is limited to 512 characters.
     */
    public $failRedirection;
    /**
     * Indicates which certificate type will be used to perform the signature and therefore which type of signature will be performed by this signer.
     * The available values are:
     * - certified Allows signers to perform a certified signature.
     * - advanced Allows signers to perform an advanced signature which requires the same options as a certified signature.
     * - simple Allows signers to perform a simple signature. The default value.
     */
    public $certificateType;
    /**
     * The ID documents to use in a signer registration. This is an option for the certified signature, if it’s set, the user won’t be prompted to provide
     * its ID documents in the RA workflow.
     */
    public $idDocuments;
    /**
     * The ID of a valid ID Validation Session retrieved from a validation request (see universign-guide-8.14.4-SNAPSHOT-ra.pdf). The documents inthis ID Validation session
     * will be used and no need to provide idDocuments.
     */
    public $validationSessionId;
    /**
     * This option allow to customize the way signers are redirect after signing documents. There are two policies:
     * - dashboard The redirection page displays the signed pages. The default value.
     * - quick The redirection page does not display the signed pages.
     * If this field is not specified, field set in TransactionRequest is used.
     */
    public $redirectPolicy;
    /**
     * The waiting time in seconds for signers to be redirected if redirectPolicy is dashboard.
     * The lower bound is "2", the upper bound is "30" and default value is "5".
     * This field must not be set if redirectPolicy is quick.
     * If this field is not specified, field set in TransactionRequest is used.
     */
    public $redirectWait;
    /**
     * Whether the subscription agreements email should be automatically sent to signer. If set to false, the email will be sent by the transaction’s operator himself.
     * If set, this value overrides the TransactionRequest one.
     */
    public $autoSendAgreements;

    /**
     * @return string
     */
    public function getFirstname():string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname():string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getOrganization():string
    {
        return $this->organization;
    }

    /**
     * @param string $organization
     */
    public function setOrganization(string $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return string
     */
    public function getProfile():string
    {
        return $this->profile;
    }

    /**
     * @param string $profile
     */
    public function setProfile(string $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return string
     */
    public function getEmailAddress():string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getPhoneNum():string
    {
        return $this->phoneNum;
    }

    /**
     * @param string $phoneNum
     */
    public function setPhoneNum(string $phoneNum): void
    {
        $this->phoneNum = $phoneNum;
    }

    /**
     * @return string
     */
    public function getLanguage():string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getRole():string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->checkValue('Role',$role,array(self::ROLE_SIGNER,self::ROLE_OBSERVER));
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getBirthDate():string
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate(\DateTime $birthDate): void
    {
        $date = date_format($birthDate,'Ymd').'T'.date_format($birthDate,'h:m:s');
        xmlrpc_set_type($date,'datetime');
        $this->birthDate = $date;
    }

    /**
     * @return string
     */
    public function getUniversignId():string
    {
        return $this->universignId;
    }

    /**
     * @param string $universignId
     */
    public function setUniversignId(string $universignId): void
    {
        $this->universignId = $universignId;
    }

    /**
     * @return RedirectionConfig
     */
    public function getSuccessRedirection():RedirectionConfig
    {
        return $this->successRedirection;
    }

    /**
     * @param RedirectionConfig $successRedirection
     */
    public function setSuccessRedirection(RedirectionConfig $successRedirection): void
    {
        $this->successRedirection = $successRedirection->getArray();
    }

    /**
     * @return RedirectionConfig
     */
    public function getCancelRedirection():RedirectionConfig
    {
        return $this->cancelRedirection;
    }

    /**
     * @param RedirectionConfig $cancelRedirection
     */
    public function setCancelRedirection(RedirectionConfig $cancelRedirection): void
    {
        $this->cancelRedirection = $cancelRedirection->getArray();
    }

    /**
     * @return RedirectionConfig
     */
    public function getFailRedirection():RedirectionConfig
    {
        return $this->failRedirection;
    }

    /**
     * @param RedirectionConfig $failRedirection
     */
    public function setFailRedirection($failRedirection): void
    {
        $this->failRedirection = $failRedirection->getArray();
    }

    /**
     * @return string
     */
    public function getCertificateType():string
    {
        return $this->certificateType;
    }

    /**
     * @param string $certificateType
     */
    public function setCertificateType(string $certificateType): void
    {
        if($certificateType !== self::CERTIFICATE_TYPE_LOCAL &&
            $certificateType !== self::CERTIFICATE_TYPE_SIMPLE &&
            $certificateType !== self::CERTIFICATE_TYPE_CERTIFIED &&
            $certificateType !== self::CERTIFICATE_TYPE_ADVANCED ){
            Throw new \InvalidArgumentException('The certificate value must be : '.self::CERTIFICATE_TYPE_LOCAL.' or '.self::CERTIFICATE_TYPE_SIMPLE.' or '.self::CERTIFICATE_TYPE_CERTIFIED.' or '.self::CERTIFICATE_TYPE_ADVANCED);
        }
        $this->certificateType = $certificateType;
    }

    /**
     * @return RegistrationRequest
     */
    public function getIdDocuments(): RegistrationRequest
    {
        return $this->idDocuments;
    }

    /**
     * @param RegistrationRequest $idDocuments
     */
    public function setIdDocuments(RegistrationRequest $idDocuments): void
    {
        $this->idDocuments = $idDocuments->getArray();
    }

    /**
     * @return string
     */
    public function getValidationSessionId():string
    {
        return $this->validationSessionId;
    }

    /**
     * @param string $validationSessionId
     */
    public function setValidationSessionId(string $validationSessionId): void
    {
        $this->validationSessionId = $validationSessionId;
    }

    /**
     * @return string
     */
    public function getRedirectPolicy():string
    {
        return $this->redirectPolicy;
    }

    /**
     * @param string $redirectPolicy
     */
    public function setRedirectPolicy(string $redirectPolicy): void
    {
        $this->redirectPolicy = $redirectPolicy;
    }

    /**
     * @return int
     */
    public function getRedirectWait():int
    {
        return $this->redirectWait;
    }

    /**
     * @param int $redirectWait
     */
    public function setRedirectWait(int $redirectWait): void
    {
        $this->redirectWait = $redirectWait;
    }

    /**
     * @return bool
     */
    public function getAutoSendAgreements():bool
    {
        return $this->autoSendAgreements;
    }

    /**
     * @param bool $autoSendAgreements
     */
    public function setAutoSendAgreements(bool $autoSendAgreements): void
    {
        $this->autoSendAgreements = $autoSendAgreements;
    }

}