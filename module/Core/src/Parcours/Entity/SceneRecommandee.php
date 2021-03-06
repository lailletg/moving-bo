<?php

namespace Parcours\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entité d'une scène recommandée
 *
 * @Gedmo\Mapping\Annotation\Loggable
 * @ORM\Entity
 * @ORM\Table(name="mbo_scenerecommandee")
 * 
 * @property Collection\Entity\TransitionRecommandee $transition_recommandee
 */
class SceneRecommandee extends Scene
{
    
    /**
     * @ORM\OneToOne(targetEntity="Parcours\Entity\TransitionRecommandee", mappedBy="scene_origine")
     **/
    protected $transition_recommandee;
    
    /**
     * @ORM\OneToOne(targetEntity="Parcours\Entity\TransitionRecommandee", mappedBy="scene_destination")
     **/
    protected $transition_recommandee_entrante;

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
     * Retourne l'objet sous forme de tableau
     *
     * @return array Objet au format tableau
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
