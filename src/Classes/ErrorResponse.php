<?php

namespace Gondellier\UniversignBundle\Classes;

class ErrorResponse
{
    /**
     * The type of error returned among api_connection_error, api_error, authentication_error, invalid_request_error, rate_limit_error
     */
    private $type;
    /**
     * For errors that can be handled programatically, a short string indicating the error code. Not always present.
     */
    private $error;
    /**
     * A human-readable message providing more details about the error.
     */
    private $error_description;
    /**
     * If the error is parameter-specific, the parameter related to the error.
     */
    private $param;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error): void
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getErrorDescription()
    {
        return $this->error_description;
    }

    /**
     * @param mixed $error_description
     */
    public function setErrorDescription($error_description): void
    {
        $this->error_description = $error_description;
    }

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param mixed $param
     */
    public function setParam($param): void
    {
        $this->param = $param;
    }

}