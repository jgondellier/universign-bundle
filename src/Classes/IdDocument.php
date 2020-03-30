<?php

namespace Gondellier\UniversignBundle\Classes;

class IdDocument extends Base
{
    private const CARTE_NATIONAL_IDENTITE = 0;
    private const PASSEPORT = 1;
    private const PERMIS_SEJOUR = 2;
    private const PERMIS_CONDUIRE_EUROPE = 3;

    public $photos;
    public $type;

    /**
     * Verify if the number of doc is the same as expected.
     *
     * @return bool
     */
    public function verifyTypeWithPhoto():bool
    {
        //Controle if 2 docs are upload
        if($this->type === self::CARTE_NATIONAL_IDENTITE || $this->type === self::PERMIS_SEJOUR){
            if (count($this->photos) !== 2){
                return False;
            }
        }else if (count($this->photos) !== 1){
            return False;
        }
        return True;
    }

    /**
     * @return array
     */
    public function getPhotos():array
    {
        return $this->photos;
    }

    /**
     * @param array $photos
     */
    public function setPhotos(array $photos): void
    {
        $this->photos = $photos;
    }

    public function addPhotos(string $photosPath):void
    {
        $photosContent  = file_get_contents($photosPath);
        xmlrpc_set_type($photosContent,'base64');
        $this->photos[] = $photosContent;
    }

    /**
     * @return int
     */
    public function getType():int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        if($type !== 0 && $type !== 1 && $type !== 2 && $type !== 3){
            Throw new \InvalidArgumentException('The type must be 0,1,2 or 3');
        }
        $this->type = $type;
    }
}