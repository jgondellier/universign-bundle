<?php

namespace Gondellier\UniversignBundle\Classes\Request;

class RedirectionConfig extends Base
{
    public $URL;
    public $displayName;

    /**
     * @return string
     */
    public function getURL():string
    {
        return $this->URL;
    }

    /**
     * @param string $URL
     */
    public function setURL(string $URL): void
    {
        $this->URL = $URL;
    }

    /**
     * @return string
     */
    public function getDisplayName():string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }


}