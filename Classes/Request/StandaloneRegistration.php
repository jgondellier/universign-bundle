<?php

namespace Gondellier\UniversignBundle\Classes\Request;

class StandaloneRegistration extends Base
{
    private const REDIRECTPOLICY_DASHBOARD = 'dashboard';
    private const REDIRECTPOLICY_QUICK = 'quick';
    /**
     * The name of the signature profile to use for the customization of the registration page.
     * Signature profiles are set up by the UNIVERSIGN team.
     * The default value is "default".
     */
    public $profile;
    /**
     * The signer that will be registered.
     */
    public $signer;
    /**
     * On registration successful completion, the user is redirected to this URL. This parameter can be defined at signer level and have higher priority in this case.
     * Length is limited to 256 characters.
     */
    public $successURL;
    /**
     * On registration cancellation, the user is redirected to this URL. This parameter can be defined at signer level and have higher priority in this case.
     * Length is limited to 256 characters.
     */
    public $cancelURL;
    /**
     * On registration failure, the user is redirected to this URL. This parameter can be defined at signer level and have higher priority in this case.
     * Length is limited to 256 characters.
     */
    public $failURL;
    /**
     * The callback URL to be requested once the standalone registration is completed (i.e the signer has a valid certificate).
     * A GET request will be performed with following parameters appended to the URL:
     *  - id : The transaction id received in TransactionResponse when requesting a standalone registration.
     *  - status : The status: VALID or INVALID.
     *  Example:  http://www.company.com/standaloneRegistration/?id=XXX-XXX-YYY&status=VALID
     */
    public $callbackURL;
    /**
     * The default registration authority operator email address. This field is used only for advanced transactions.
     * This address must match with a well-known registration authority operator by Universign. It is only used to send the transaction creation email.
     * If not specified, the email is sent to the transaction creator.
     */
    public $operator;
    /**
     * This option allow to customize the way signers are redirect after signing documents. There are two policies:
     * - dashboard : The redirection page displays the signed pages. The default value.
     * - quick The redirection page does not display the signed pages.
     * This field can be overridden in TransactionSigner for a specific signer.
     */
    public $redirectPolicy;
    /**
     * The waiting time in seconds for signers to be redirected if redirectPolicy is dashboard.
     * The lower bound is "2", the upper bound is "30" and default value is "5".
     * This field must not be set if redirectPolicy is quick. This field can be overridden in TransactionSigner for a specific signer.
     */
    public $redirectWait;
    /**
     * The url where the signer will be redirected when an advanced signature is interrupted after the automatic validation step.
     * If it is null, the signer is redirected to the Universign corporate website. Length is limited to 256 characters.
     */
    public $autoValidationURL;


    /**
     * @param string $profile
     */
    public function setProfile(string $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @param TransactionSigner $signer
     */
    public function setSigner(TransactionSigner $signer): void
    {
        $this->signer = $signer->getArray();
    }

    /**
     * @param string $successURL
     */
    public function setSuccessURL(string $successURL): void
    {
        $this->successURL = $successURL;
    }

    /**
     * @param string $cancelURL
     */
    public function setCancelURL(string $cancelURL): void
    {
        $this->cancelURL = $cancelURL;
    }

    /**
     * @param string $failURL
     */
    public function setFailURL(string $failURL): void
    {
        $this->failURL = $failURL;
    }

    /**
     * @param string $callbackURL
     */
    public function setCallbackURL(string $callbackURL): void
    {
        $this->callbackURL = $callbackURL;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @param string $redirectPolicy
     */
    public function setRedirectPolicy(string $redirectPolicy): void
    {
        $this->checkValue('RedirectPolicy',$redirectPolicy,array(self::REDIRECTPOLICY_DASHBOARD,self::REDIRECTPOLICY_QUICK));
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
     * @param string $autoValidationURL
     */
    public function setAutoValidationURL(string $autoValidationURL): void
    {
        $this->autoValidationURL = $autoValidationURL;
    }


}