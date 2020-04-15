<?php

namespace Gondellier\UniversignBundle\Classes;

class TransactionRequest extends Base
{
    private const CERTIFICATE_TYPE_LOCAL        = 'local';
    private const CERTIFICATE_TYPE_SIMPLE       = 'simple';
    private const CERTIFICATE_TYPE_CERTIFIED    = 'certified';
    private const CERTIFICATE_TYPE_ADVANCED     = 'advanced';
    /**
     * The name of the signature profile to use. Signature profiles mainly differ by the displayed company name and logo, and the pre-configured signature
     * field stored within. Signature profiles are set up by the UNIVERSIGN team. The default value is "default".
     */
    public $profile;
    /**
     * A requester-set unique id that can be used to identify this transaction. If not unique, a fault will be thrown. Note that UNIVERSIGN generate its own
     * unique id for each transaction and return it to the requester.
     */
    public $customId;
    /**
     * The signers that will have to take part to the transaction. Must contain at least one element.
     */
    public $signers;
    /**
     * The documents to be signed. Must contain at least one element.
     * The size limit of each document is set to 10Mo.
     */
    public $documents;
    /**
     * If set to True, the first signer will receive an invitation to sign the document(s) by e-mail as soon as the transaction is requested. False by default.
     */
    public $mustContactFirstSigner;
    /**
     * Tells whether each signer must receive the signed documents by e-mail when the transaction is completed.
     * False by default.
     */
    public $finalDocRequesterSent;
    /**
     * Tells whether the requester must receive the signed documents via e-mail when the transaction is completed.
     * False by default.
     */
    public $finalDocSent;
    /**
     * Tells whether the observers must receive the signed documents via e-mail when the transaction is completed.
     * It takes the finalDocSent value by default.
     */
    public $finalDocObserverSent;
    /**
     * Description or title of the signature.
     */
    public $description;
    /**
     * Option that indicates which certificate type will be used to perform the signature (and therefore which type of signature is expected).
     * The available values are:
     * - simple Allows signers to perform a level 1 signature.
     * - certified Allows signers to perform a level 2 signature.
     * - advanced Allows signers to perform a level 3 signature.
     * The default value is simple.
     */
    //public $certificateType;
    /**
     * The interface language for this transaction. The valid values are:
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
     * The mode to enable the handwritten signature. There are three modes:
     * - "0": disables the hand-written signature
     * - "1": enables the hand-written signature
     * - "2": enables the hand-written signature if only it is a touch interface
     * If handwritten signature is enabled, the signer is prompted to draw a signature on the web interface and the SignatureField bean becomes mandatory for each of the
     * TransactionSigners. This signature is added in his signature field, as an image would be.
     * HandwrittenSignatureMode can not be enabled against a transaction containing only document for presentation.
     */
    public $handwrittenSignatureMode;
    /**
     * This option indicates how the signers are chained during the signing process. The valid values are:
     * - none : No invitation email is sent in this mode. Each signer is redirected to the successURL after signing. It is up to the requester to contact each of the signers.
     * - email : The default value. The signers receive the invitation email (except for the first one, see mustContactFirstSigner) and are redirected to the successURL.
     * - web : Enables the linked signature mode. In this mode, all signers are physically at the same place. After a signer completed its signature, he will be redirected
     * to the next signer’s signature page instead of being returned to the successURL and the next signer will not receive an invitation mail. The last signer will be redirected
     * to the successURL.
     */
    public $chainingMode;
    /**
     * This option allows to send a copy of the final signed documents to a list of email addresses. This copy is send as cc for every final signed documents email addressed
     * to a signer. For this option to be taken into account, the option finalDocSent must be sent to True.
     */
    public $finalDocCCeMails;
    /**
     * The url where the signer will be redirected when an advanced signature is interrupted after the automatic validation step.
     * If it is null, the signer is redirected to the Universign corporate website.
     * Length is limited to 256 characters.
     */
    public $autoValidationURL;
    /**
     * This option allow to customize the way signers are redirect after signing documents.
     * There are two policies:
     * - dashboard : The redirection page displays the signed pages. The default value.
     * - quick : The redirection page does not display the signed pages.
     * This field can be overridden in TransactionSigner for a specific signer.
     */
    public $redirectPolicy;
    /**
     * The waiting time in seconds for signers to be redirected if redirectPolicy is dashboard.
     * The lower bound is "2", the upper bound is "30" and default value is "5".
     * This field must not be set if redirectPolicy is quick.
     * This field can be overridden in TransactionSigner for a specific signer.
     */
    public $redirectWait;
    /**
     * Whether the subscription agreements email should be automatically sent to signers.
     * If set to false, the email will be sent by the transaction’s operator himself. This field can be overridden in TransactionSigner for a specific signer.
     */
    public $autoSendAgreements;
    /**
     * The default registration authority operator email address.
     * This field is used only for advanced transactions.
     * This address must match with a wellknown registration authority operator by Universign.
     * It is only used to send the transaction creation email.
     * If not specified, the email is sent to the transaction creator.
     */
    public $operator;
    /**
     * The callback URL to be requested when the RA validation is completed. This field is used only for advanced transactions.
     * A GET request will be performed with following parameters appended to the URL:
     * - id : The transaction id received in TransactionResponse.
     * - signer : The index of the current signer of the transaction.
     * - status : The RA status: INVALID or AWAITING_AGREEMENT.
     *  Example: http://www.company.com/registration/?id=XXX-XXX-YYY&signer=2&status=AWAITING_AGREEMENT
     */
    public $registrationCallbackURL;

    /**
     * The configuration of the signer redirection in the event that the signing process is successfully completed.
     */
    public $successRedirection;

    /**
     * The configuration of the signer redirection in the event that the signing process is canceled.
     */
    public $cancelRedirection;

    /**
     * The configuration of the signer redirection in the event that the signing process fails.
     */
    public $failRedirection;

    /**
     * A custom message added to the invitation email for signing for every signer.
     * This field can be overridden in TransactionSigner for a specific signer.
     */
    public $invitationMessage;

    /**
     * @param string $profile
     */
    public function setProfile(string $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @param string $customId
     */
    public function setCustomId(string $customId): void
    {
        $this->customId = $customId;
    }

    /**
     * @param bool $mustContactFirstSigner
     */
    public function setMustContactFirstSigner(bool $mustContactFirstSigner): void
    {
        $this->mustContactFirstSigner = $mustContactFirstSigner;
    }

    /**
     * @param bool $finalDocRequesterSent
     */
    public function setFinalDocRequesterSent(bool $finalDocRequesterSent): void
    {
        $this->finalDocRequesterSent = $finalDocRequesterSent;
    }

    /**
     * @param bool $finalDocSent
     */
    public function setFinalDocSent(bool $finalDocSent): void
    {
        $this->finalDocSent = $finalDocSent;
    }

    /**
     * @param bool $finalDocObserverSent
     */
    public function setFinalDocObserverSent(bool $finalDocObserverSent): void
    {
        $this->finalDocObserverSent = $finalDocObserverSent;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $certificateType
     */
    /*public function setCertificateType(string $certificateType): void
    {
        if($certificateType !== self::CERTIFICATE_TYPE_LOCAL &&
            $certificateType !== self::CERTIFICATE_TYPE_SIMPLE &&
            $certificateType !== self::CERTIFICATE_TYPE_CERTIFIED &&
            $certificateType !== self::CERTIFICATE_TYPE_ADVANCED ){
            Throw new \InvalidArgumentException('The certificate value must be : '.self::CERTIFICATE_TYPE_LOCAL.
                ' or '.self::CERTIFICATE_TYPE_SIMPLE.
                ' or '.self::CERTIFICATE_TYPE_CERTIFIED.
                ' or '.self::CERTIFICATE_TYPE_ADVANCED);
        }
        $this->certificateType = $certificateType;
    }*/

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @param int $handwrittenSignatureMode
     */
    public function setHandwrittenSignatureMode(int $handwrittenSignatureMode): void
    {
        if($handwrittenSignatureMode!==0 && $handwrittenSignatureMode !==1 && $handwrittenSignatureMode !==2){
            Throw new \InvalidArgumentException('The handwrittenSignatureMode value must be : 0 or 1 or 2.');
        }
        $this->handwrittenSignatureMode = $handwrittenSignatureMode;
    }

    /**
     * @param string $chainingMode
     */
    public function setChainingMode(string $chainingMode): void
    {
        $this->chainingMode = $chainingMode;
    }

    /**
     * @param array $signers
     */
    public function setSigners(array $signers): void
    {
        $this->signers = $signers;
    }
    public function addSigner(TransactionSigner $signer): void
    {
        $this->signers[] = $signer->getArray();
    }

    /**
     * @param array $documents
     */
    public function setDocuments(array $documents): void
    {
        foreach($documents as $document){
            /**@var TransactionDocument $document*/
            $this->documents[] = $document->getArray();
        }
    }

    /**
     * @param array $finalDocCCeMails
     */
    public function setFinalDocCCeMails(array $finalDocCCeMails): void
    {
        $this->finalDocCCeMails = $finalDocCCeMails;
    }

    /**
     * @param array $autoValidationURL
     */
    public function setAutoValidationURL(array $autoValidationURL): void
    {
        $this->autoValidationURL = $autoValidationURL;
    }

    /**
     * @param string $redirectPolicy
     */
    public function setRedirectPolicy(string $redirectPolicy): void
    {
        $this->redirectPolicy = $redirectPolicy;
    }

    /**
     * @param int $redirectWait
     */
    public function setRedirectWait(int $redirectWait): void
    {
        $this->redirectWait = $redirectWait;
    }

    /**
     * @param bool $autoSendAgreements
     */
    public function setAutoSendAgreements(bool $autoSendAgreements): void
    {
        $this->autoSendAgreements = $autoSendAgreements;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @param string $registrationCallbackURL
     */
    public function setRegistrationCallbackURL(string $registrationCallbackURL): void
    {
        $this->registrationCallbackURL = $registrationCallbackURL;
    }

    /**
     * @param array $successRedirection
     */
    public function setSuccessRedirection(array $successRedirection): void
    {
        $this->successRedirection = $successRedirection;
    }

    /**
     * @param array $cancelRedirection
     */
    public function setCancelRedirection(array $cancelRedirection): void
    {
        $this->cancelRedirection = $cancelRedirection;
    }

    /**
     * @param array $failRedirection
     */
    public function setFailRedirection(array $failRedirection): void
    {
        $this->failRedirection = $failRedirection;
    }

    /**
     * @param string $invitationMessage
     */
    public function setInvitationMessage(string $invitationMessage): void
    {
        $this->invitationMessage = $invitationMessage;
    }

}