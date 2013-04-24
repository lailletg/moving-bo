<?php
/**
 *
 */

namespace Collection\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Collection\Form\ChampTypeElementForm;
use Zend\Form\Form;
use Zend\Form\Element;
use Collection\View\Helper\formatForm;
use Exception;
use Collection\Entity\Media;
use Collection\Entity\Data;

class MediaController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	
	/**
	 * Return a EntityManager
	 *
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		if ($this->em === null) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
	
		return $this->em;
	}

	public function indexAction()
    {
    	return $this->redirect()->toRoute('collection/consulter');
    }

    public function ajouterAction()
    {
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		// Le formulaire d'ajout a été posté
    		// On récupère le type de média que l'on va créer
	    	$nom_type_element = $this->params()->fromRoute('type_element');
	    	$type_element = $this->getEntityManager()->getRepository('Collection\Entity\TypeElement')->findOneBy(array('type'=>'media', 'nom'=>$nom_type_element));
	    	if (!$type_element) {
	    		throw new Exception('Type d\'élément non trouvé au moment de la création du média');
	    	}
	    	$form = new ChampTypeElementForm($type_element);
	    	$form->setInputFilter($album->getInputFilter());
	    	$form->setData($request->getPost());
	    	$artefact = new Media(null, $type_element);
	    	if ($form->isValid()) {
	    		$datas = $form->getData();
	    		// On initialise les champs id, titre et description du média
	    		$artefact->populate($datas);
	    		// Reste tous les autres : il faut créer des entity 'data'
	    		foreach ($datas as $label => $data) {
	    			// on passe les champs inutils
	    			if ($label != 'id' && $label != 'titre' && $label != 'description' && $label != 'submit') {
	    				// On vérifie que ce type d'artefact a bien un champ qui porte ce nom
	    				$champ = $this->getEntityManager()->getRepository('Collection\Entity\Champ')->findOneBy(array('label'=>$label, 'type_element'=>$type_element));
	    	 			if (!$champ) {
	    	 				throw new Exception('Champ '.$label.' non trouvé.');
	    	 			}
	    	 			// On créer l'entity data qui correspond à ce champ pour cet artefact
	    	 			$databd = new Data($artefact, $champ);
	    	 			switch ($champ->format) {
	    	 				case 'texte':
	    	 					$databd->texte = $data;    	 					
	    	 					break;
	    	 				case 'textarea':
	    	 					$databd->textarea = $data;
	    	 					break;
	    	 				case 'date':
	    	 					$databd->date = $data;
	    	 					break;
	    	 				case 'nombre':
	    	 					$databd->nombre = $data;
	    	 					break;
	    	 				case 'fichier':
	    	 					$databd->fichier = $data;
	    	 					break;
	    	 				case 'url':
	    	 					$databd->url = $data;
	    	 			} // end switch
	    	 			$this->getEntityManager()->persist($databd);
	    			}
	    		}
	    		$this->getEntityManager()->persist($artefact);
	    		$this->getEntityManager()->flush();
	    		return $this->redirect()->toRoute('collection/consulter');
	    	} else {
	    		throw new Exception ('Formulaire non valide');
	    	}
    	}
    	$TEmedias = $this->getEntityManager()->getRepository('Collection\Entity\TypeElement')->findBy(array('type'=>'media'));
		return new ViewModel(array('types' => $TEmedias));
    }

    public function getFormAjaxAction()
    {
    	if ($this->getRequest()->isXmlHttpRequest()) 
        {
            $type = $this->params()->fromPost('type');
            
        	$TEmedia = $this->getEntityManager()->getRepository('Collection\Entity\TypeElement')->findOneBy(array('type'=>'media', 'nom'=>$type));
        	$form = new ChampTypeElementForm($TEmedia);

        	$viewModel = new ViewModel(array('success' => true, 'type' => $type, 'form' => $form));
        	$viewModel->setTerminal(true);
        	return $viewModel;

        } else {
        	return $this->redirect()->toRoute('media/ajouter');
        }
    }
}