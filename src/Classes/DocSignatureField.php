<?php

namespace Gondellier\UniversignBundle\Classes;

class DocSignatureField extends Base
{
    /**
     * The index of the signer which uses this field. Signers are enumerated starting at 0.
     */
    public $signerIndex;
    /**
     * The name of the pattern. May be used if more than one pattern is set. The default value is "default". The magic value "invisible" means that the field
     * will not be visible in the PDF.
     */
    public $patternName;
    /**
     * A label which defines the signature field. This label will be printed in the signature page if set. If a signer has more than one field on the same document,
     * label becomes mandatory.
     */
    public $label;
    /**
     * The image to be displayed in the signature field, it will replace the default UNIVERSIGN logo. Image format must be JPG, JPEGor PNG.
     * A recommended resolution for this image is 150x36px. The image will be resized if the image has a different resolution.
     */
    public $image;
    /**
     * The name of the field. If the PDF already contains a named signature field, you can use this parameter instead of giving the coordinates (which will be ignored).
     * If the name of this field does not exist in the document, the given coordinates will be used instead.
     */
    public $name;
    /**
     * The page on which the field must appear (starting at ’1’ for the first page).
     * Pages are enumerated starting at 1. The value ’-1’ points at the last page.
     */
    public $page;
    /**
     * The field horizontal coordinate on the page.
     */
    public $x;
    /**
     * The field vertical coordinate on the page.
     */
    public $y;

    /**
     * @param mixed $signerIndex
     */
    public function setSignerIndex($signerIndex): void
    {
        $this->signerIndex = $signerIndex;
    }

    /**
     * @param mixed $patternName
     */
    public function setPatternName($patternName): void
    {
        $this->patternName = $patternName;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $imageContent  = file_get_contents($image);
        xmlrpc_set_type($imageContent,'base64');
        $this->image = $imageContent;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    public function check():void
    {
        if(empty($this->name) || (empty($this->page) && empty($this->x) && empty($this->y)) ){
            Throw new \InvalidArgumentException('At least name or page,x and y must be filled.');
        }
        if($this->signerIndex === '' || $this->signerIndex === null ){
            Throw new \InvalidArgumentException('SignerIndex must be filled.');
        }
    }
}