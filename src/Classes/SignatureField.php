<?php

namespace Gondellier\UniversignBundle\Classes;

class SignatureField extends Base
{
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

}