<?php

namespace Gondellier\UniversignBundle\Classes;

class SEPAData extends Base
{
    /**
     * A unique mandate identifier.
     */
    public $rum;
    /**
     * A unique creditor identifier.
     */
    public $ics;
    /**
     * The debtor International Bank Account Number.
     */
    public $iban;
    /**
     * The debtor Bank Identifier Code.
     */
    public $bic;
    /**
     * Whether this SEPA mandate describe a recurring payment (true) or a single-shot payement (false).
     */
    public $recurring;
    /**
     * Information on the debtor.
     */
    public $debtor;
    /**
     * Information on the creaditor.
     */
    public $creditor;

    /**
     * @param mixed $rum
     */
    public function setRum($rum): void
    {
        $this->rum = $rum;
    }

    /**
     * @param mixed $ics
     */
    public function setIcs($ics): void
    {
        $this->ics = $ics;
    }

    /**
     * @param mixed $iban
     */
    public function setIban($iban): void
    {
        $this->iban = $iban;
    }

    /**
     * @param mixed $bic
     */
    public function setBic($bic): void
    {
        $this->bic = $bic;
    }

    /**
     * @param mixed $recurring
     */
    public function setRecurring($recurring): void
    {
        $this->recurring = $recurring;
    }

    /**
     * @param SEPAThirdParty $debtor
     */
    public function setDebtor(SEPAThirdParty $debtor): void
    {
        $this->debtor = $debtor->getArray();
    }

    /**
     * @param SEPAThirdParty $creditor
     */
    public function setCreditor(SEPAThirdParty $creditor): void
    {
        $this->creditor = $creditor->getArray();
    }


}