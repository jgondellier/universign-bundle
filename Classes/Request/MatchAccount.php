<?php

namespace Gondellier\UniversignBundle\Classes\Request;

class MatchAccount extends Base
{
    public $firstname;
    public $lastname;
    public $email;
    public $mobile;

    /**
     * Le prenom a rechercher
     *
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * Le nom a rechercher
     *
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * L'email a rechercher
     *
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Le telephone portable a rechercher
     *
     * @param mixed $mobile
     */
    public function setMobile($mobile): void
    {
        $this->mobile = $mobile;
    }

}