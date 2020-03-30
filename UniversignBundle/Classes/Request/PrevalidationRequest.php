<?php


namespace Gondellier\UniversignBundle\Classes\Request;


class PrevalidationRequest
{
    private const DATE_FORMAT = 'Y-m-d';
    /**
     * The identity document, input as 1 or 2 files.
     * Identity documents sent for prevalidation must comply with the following prerequisites:
     * Size: the document must not exceed 4MB.
     * Format: the document must be provided as an image (JPEG or PNG) or a PDF file.
     * In how many files should you input the identity document?
     * Passports must be input as a single file in your request (information page only).
     * French identity cards can be input as a single file (front and back sides in the same file) or as two separate files (front side in one file and back side in another file).
     */
    private $file;
    /**
     * profile determines the type of certificate that can be issued with the provided identity document.
     * Set the value to medium if you require a standard certificate (used for level 2 signature transactions) or high if you require a qualified certificate
     * (used for level 3 signature transactions).
     * With medium profiles:
     *      There is no default value for the color_required parameter, which means that black and white documents are accepted if you don't specify otherwise.
     *      If you accept color documents only, you must set the value to true. The accepted value for expires_after must be higher than the current date less two years,
     *      which means that IDs expired since less than 2 years are accepted.
     * With high profiles:
     *     The default value for the color_required parameter is true, which means that only color documents are accepted.
     *     This default value cannot be overidden with a less restrictive value, which is why you will be returned an error if you set the color_required value to false
     *     with a high profile. The accepted value for expires_after must be equal to or higher than the current date, which means that IDs must be valid at current date or beyond.
     */
    private $profile;
    /**
     * Used to restrict the type of accepted IDs. Value(s) can be id_card:fra for French ID cards, passport:* for passports. Don't forget to replace * with the three-letter
     * country code corresponding to the accepted country. If you accept all European passports, replace * with eu. View the list of ISO 3166-1 alpha-3 codes
     */
    private $document_type;
    /**
     *  If you require a color document, set the value to true. If you set the profile to high DO NOT set the value to false or you will be returned a 400 error.
     */
    private $color_required;
    /**
     * The ID bearer's lastname. Can be input in lower or upper case (or both), with or without accent, with or without hyphen, with or without apostrophe.
     */
    private $family_name;
    /**
     * The ID bearer's firstname. Can be input in lower or upper case (or both), with or without accent, with or without hyphen, with or without apostrophe.
     */
    private $given_name;
    /**
     * The ID bearer's date of birth. Expected date format is YYYY-MM-DD.
     */
    private $birth_date;
    /**
     * The date after which the ID is allowed to be expired. This parameter is used to indicate whether an ID is accepted even if expired since less than 2 years,
     * if an ID must be valid on prevalidation date or if it must be valid at a specific later date. Expected date format is YYYY-MM-DD.
     */
    private $expires_after;

    /**
     * @return array Les paramètres pour faire l'appel APIREST
     */
    public function getRequestParams():array
    {
        $params = array();
        foreach ($this as $key => $value) {
            if($value!==null && $value!=='' ){
                if (is_array($value)){
                    foreach($value as $val){
                        if(is_array($val) && array_key_exists('stream',$val)){
                            $params[] = ['name' => $key,'contents' => $val['stream'],'filename' => $val['name']];
                        }else{
                            $params[] = ['name' => $key,'contents' => $val];
                        }
                    }
                }else{
                    $params[] = ['name' => $key,'contents' => $value];
                }
            }
        }

        return $params;
    }

    /**
     * @return array
     */
    public function getFile():array
    {
        return $this->file;
    }

    /**
     * @param array $file
     */
    public function setFile(array $file): void
    {
        $this->file = $file;
    }

    /**
     * @param string $filePath Chemin du fichier
     * @param string $fileName Nom du fichier
     */
    public function addFile(string $filePath, string $fileName): void
    {
        $this->file[] = ['name' => $fileName,'stream' => fopen($filePath, 'rb')];
    }

    /**
     * @return string
     */
    public function getProfile():string
    {
        return $this->profile;
    }

    /**
     * @param string $profile
     */
    public function setProfile(string $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return array
     */
    public function getDocumentType(): array
    {
        return $this->document_type;
    }

    /**
     * @param array $document_type
     */
    public function setDocumentType(array $document_type): void
    {
        $this->document_type = $document_type;
    }

    /**
     * @param string $documentType
     */
    public function addDocumentType(string $documentType): void
    {
        $this->document_type[] = $documentType;
    }

    /**
     * @return bool
     */
    public function getColorRequired(): bool
    {
        return $this->color_required;
    }

    /**
     * @param bool $color_required
     */
    public function setColorRequired(bool $color_required): void
    {
        $this->color_required = $color_required;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->family_name;
    }

    /**
     * @param string $family_name
     */
    public function setFamilyName(string $family_name): void
    {
        $this->family_name = $family_name;
    }

    /**
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->given_name;
    }

    /**
     * @param string $given_name
     */
    public function setGivenName(string $given_name): void
    {
        $this->given_name = $given_name;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate(): \DateTime
    {
        return $this->birth_date;
    }

    /**
     * @param \DateTime $birth_date
     */
    public function setBirthDate(\DateTime $birth_date): void
    {
        $this->birth_date = date_format($birth_date,self::DATE_FORMAT);
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAfter(): \DateTime
    {
        return $this->expires_after;
    }

    /**
     * @param \DateTime $expires_after
     */
    public function setExpiresAfter(\DateTime $expires_after): void
    {
        $this->expires_after = date_format($expires_after,self::DATE_FORMAT);
    }



}