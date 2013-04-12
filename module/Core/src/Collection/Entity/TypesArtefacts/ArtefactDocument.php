<?php

namespace Collection\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
* Un document
*
* @ORM\Entity
* @property string $editeur
* @property string $auteur
* @property string $langue
* @property date $date
*/
class ArtefactDocument extends Artefact
{
    protected $inputFilter;

    /**
    * @ORM\Column(type="string", length=100)
    */
    protected $editeur;
    
    /**
    * @ORM\Column(type="string", length=100)
    */
    protected $auteur;
    
    /**
    * @ORM\Column(type="string", length=100)
    */
    protected $langue;
    
    /**
    * @ORM\Column(type="date")
    */
    protected $date;
    
    /**
    * Magic getter to expose protected properties.
    *
    * @param string $property
    * @return mixed
    */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
    * Magic setter to save protected properties.
    *
    * @param string $property
    * @param mixed $value
    */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
    * Convert the object to an array.
    *
    * @return array
    */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
    * Populate from an array.
    *
    * @param array $data
    */
    public function populate($data = array())
    {

    }

     public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
       
    }
}
