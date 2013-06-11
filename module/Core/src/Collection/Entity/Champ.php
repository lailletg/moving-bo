<?php

namespace Collection\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use InvalidArgumentException;
use Doctrine\ORM\EntityRepository;

/**
 * Entité d'un champ d'un type d'élément
 *
 * @Gedmo\Mapping\Annotation\Loggable
 * @ORM\Entity
 * @ORM\Table(name="mbo_champ")
 * 
 * @property int $id Identifiant unique du champ
 * @property string $label Label du champ (nom du champ)
 * @property string $description Description du champ (sert d'aide à la saisie pour l'utilisateur)
 * @property string $format Format du champ, la valeur doit faire appartenir à une liste de valeurs possibles prédéfiniées : 'texte', 'textarea', 'nombre', 'fichier', 'url', 'date'
 * @property \Collection\Entity\Data $datas L'ensemble des datas pour tous les éléments décrits par ce champ
 * @property \Collection\Entity\TypeElement $type_element Le type d'élément qui est décrit par ce champ
 * @property bool $valide Booléen qui décrit si le type d'élément est validé ou brouillon
 */
class Champ implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $label;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $format;
    
    /**
     * @ORM\OneToMany(targetEntity="Collection\Entity\Data", mappedBy="champ", cascade={"remove"}))
     **/
    protected $datas;
    
    /**
     * @ORM\ManyToOne(targetEntity="Collection\Entity\TypeElement", inversedBy="champs")
     **/
    protected $type_element;

    /**
     * @ORM\Column(type="boolean")
     **/
    protected $valide = false;

    /**
     * Constructeur
     * 
     * @param string $label Valeur par défaut : chaine vide
     * @param \Collection\Entity\TypeElement $type_element Valeur par défaut : null
     * @param string $format Valeur par défaut : chaine vide
     * @throws InvalidArgumentException Si le paramètre format ne fait pas partie des format reconnus
     */
    public function __construct($label = '', $type_element = null, $format = 'texte') {
    	$this->label = $label;
    	$this->type_element = $type_element;
    	if ($format != "texte" 
    			&& $format != "textarea"
    			&& $format != "date"
    			&& $format != "fichier"
    			&& $format != "nombre"
    			&& $format != "url") {
    		throw new InvalidArgumentException("Construction d'un objet Champ avec un format interdit");
    	}
    	$this->format = $format;
    }
    
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
     * Retourne l'objet sous la forme d'un tableau
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
    	$this->label = $data['label'];
    	$this->description = $data['description'];
    	$this->format = $data['format'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory = new InputFactory();
    		
    		$inputFilter->add($factory->createInput(array(
    				'name' => 'id',
    				'required' => true,
    				'filters' => array(array('name' => 'Int')),
    		)));
    	
    		$inputFilter->add($factory->createInput(array(
    			'name' => 'label',
    			'required' => true,
    			'filters' => array(
    				array('name' => 'StripTags'),
    				array('name' => 'StringTrim'),
    			),
    			'validators' => array(
    				array(
    					'name' => 'StringLength',
    					'options' => array(
    						'encoding' => 'UTF-8',
    						'min' => 1,
    						'max' => 200,
    					),
    				),
    			),
    		)));
    	
    		$inputFilter->add($factory->createInput(array(
    			'name' => 'description',
                'required' => false,
    			'filters' => array(
    				array('name' => 'StripTags'),
    				array('name' => 'StringTrim'),
    			),
    		)));
    	
    		$this->inputFilter = $inputFilter;
    	}
    		
    	return $this->inputFilter;
    }

    /**
     * Méthode pour supprimer les fichiers associés à un champ
     * 
     * Cette méthode va supprimer le répertoire contenant les fichiers uploadés pour ce champ
     * Elle est utile lorsqu'un administrateur supprime un champ par exemple
     * 
     * @return boolean
     */
    public function deleteFiles(){
    	if($this->format === 'fichier'){
    		$dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
    		 
    		if( $this->type_element->type === 'media' ){
    			$dir .= 'medias/';
    		} else if( $this->type_element->type === 'artefact' ) {
    			$dir .= 'artefacts/';
    		}
    		 
    		$dir .= (string)$this->id . '/';
    
    		$this->delTree($dir);
    		return true;
    	}
    	return false;
    }
    
    /**
     * Supprime un dossier et son contenu
     * 
     * Supprime le dossier et tout ce qu'il contient récursivement.
     * Méthode utilisée par deleteFiles()
     * Crédit : http://fr2.php.net/manual/fr/function.rmdir.php#92661
     * 
     * @param string
     * @return boolean
     */
    private function delTree($dir) {
    	if(is_dir($dir)){
    		$files = glob( $dir . '*', GLOB_MARK );
    		foreach( $files as $file ){
    			$file = str_replace('\\', '/', $file);
    			if( substr( $file, -1 ) == '/' ) {
    				$this->delTree( $file );
    			} else {
    				if( is_file($file) ){
    					chown( $file, 666 );
    					chmod( $file, 0666 );
    					unlink( $file );
    				}
    			}
    		}
    		rmdir( $dir );
    		return true;
    	}
    	return false;
    }
    
}
