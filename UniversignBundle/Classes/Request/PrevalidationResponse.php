<?php


namespace Gondellier\UniversignBundle\Classes\Request;


class PrevalidationResponse
{
    private $prevalidation_token;
    private $extraction_success;
    private $successful_checks;
    private $failed_checks;
}