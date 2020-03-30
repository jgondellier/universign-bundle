<?php

namespace Gondellier\UniversignBundle\Classes\Request;

class TransactionDocument extends Base
{
    private const DOCUMENT_TYPE_PDF                     = 'pdf';
    private const DOCUMENT_TYPE_PDF_FOR_PRESENTATION    = 'pdf-for-presentation';
    private const DOCUMENT_TYPE_PDF_OPTIONAL            = 'pdf-optional';
    private const DOCUMENT_TYPE_SEPA                    = 'sepa';

    /**
     * This TransactionDocument type. Valid values are:
     * - pdf : The default value. Makes all TransactionDocument members relevant, except for SEPAData.
     * - pdf-for-presentation : This value marks the document as view only.
     * - pdf-optional : This type of PDF document can be refused and not signed by any signer without canceling the transaction.
     * - sepa : Using this value, no PDF document is provided, but UNIVERSIGN creates a SEPA mandate from data sent in SEPAData, which becomes the single relevant member.
     */
    public $documentType;
    /**
     * The raw content of the PDF document. You can provide the document using the url field, otherwise this field is mandatory.
     */
    public $content;
    /**
     * The URL to download the PDF document. Note that this field is mandatory if the content is not set.
     */
    public $url;
    /**
     * The file name of this document.
     */
    public $fileName;
    /**
     * A description of a visible PDF signature field.
     */
    public $signatureFields;
    /**
     * Texts of the agreement checkboxes. The last one should be the text of the checkbox related to signature fields labels agreement.
     * This attribute should not be used with documents of the type "pdf-for-presentation".
     * Since agreement for "pdf-for-presentation" is not customisable.
     */
    public $checkBoxTexts;
    /**
     * This structure can only contain simple types like integer, string or date.
     */
    public $metaData;
    /**
     * A title to be used for display purpose.
     */
    public $title;
    /**
     * A structure containing data to create a SEPA mandate PDF.
     */
    public $SEPAData;

    /**
     * @param string $documentType
     */
    public function setDocumentType(string $documentType): void
    {
        if($documentType !== self::DOCUMENT_TYPE_PDF &&
            $documentType !== self::DOCUMENT_TYPE_PDF_FOR_PRESENTATION &&
            $documentType !== self::DOCUMENT_TYPE_PDF_OPTIONAL &&
            $documentType !== self::DOCUMENT_TYPE_SEPA ){
            Throw new \InvalidArgumentException('The documentType field should be : '.self::DOCUMENT_TYPE_PDF.
                ' or '.self::DOCUMENT_TYPE_PDF_FOR_PRESENTATION.
                ' or '.self::DOCUMENT_TYPE_PDF_OPTIONAL.
                ' or '.self::DOCUMENT_TYPE_SEPA
            );
        }
        $this->documentType = $documentType;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $documentContent  = file_get_contents($content);
        xmlrpc_set_type($documentContent,'base64');
        $this->content = $documentContent;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        if(empty($fileName)){
            Throw new \InvalidArgumentException('The document filename must not be empty.');
        }
        $this->fileName = $fileName;
    }

    /**
     * @param array $signatureFields
     */
    public function setSignatureFields(array $signatureFields): void
    {
        $this->signatureFields = $signatureFields;
    }

    /**
     * @param array $checkBoxTexts
     */
    public function setCheckBoxTexts(array $checkBoxTexts): void
    {
        $this->checkBoxTexts = $checkBoxTexts;
    }

    /**
     * @param mixed $metaData
     */
    public function setMetaData($metaData): void
    {
        $this->metaData = $metaData;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param SEPAData $SEPAData
     */
    public function setSEPAData(SEPAData $SEPAData): void
    {
        $this->SEPAData = $SEPAData->getArray();
    }

    public function check():void
    {
        if(empty($this->content) && empty($this->url) ){
            Throw new \InvalidArgumentException('At least content or url must be not empty.');
        }
        if(empty($this->fileName)){
            Throw new \InvalidArgumentException('fileName must be filled.');
        }
        if(empty($this->documentType)){
            Throw new \InvalidArgumentException('documentType must be filled.');
        }
    }
}