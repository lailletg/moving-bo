<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Parcours\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Parcours\Entity\Parcours;
use Parcours\Entity\SousParcours;
use Parcours\Form\ParcoursForm;
use Parcours\Entity\TransitionRecommandee;
use Parcours\Entity\SceneRecommandee;
use Zend\Json\Json;

/**
 * Controleur des parcours
 *
 * Permet la création, lecture, modification et suppression d'un parcours
 *
 * @property Doctrine\ORM\EntityManager $em Entity Manager
 */
class ParcoursController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Initialisation de l'Entity Manager
	 *
	 * @param Doctrine\ORM\EntityManager
	 * @return void
	 */
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * Retourne l'Entity Manager
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

	/**
	 * Affiche la liste des parcours
	 * 
	 */
    public function indexAction()
    {
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $escapeHtml = $viewHelperManager->get('escapeHtml');
    	$params = null;

    	if ($this->getRequest()->isXmlHttpRequest()) {
    		$params = $this->params()->fromPost();
    	}
    	
    	if(!isset($params["iSortCol_0"])){
    		$params["iSortCol_0"] = '0';
    	}

    	if(!isset($params["sSortDir_0"])){
    		$params["sSortDir_0"] = 'ASC';
    	}
    	
    	$entityManager = $this->getEntityManager()
    					      ->getRepository('Parcours\Entity\Parcours');
 
    	$dataTable = new \Parcours\Model\ParcoursDataTable($params);
    	$dataTable->setEntityManager($entityManager);
    
    	$dataTable->setConfiguration(array(
    		'titre',
	        'description'
    	));
    
    	$aaData = array();
    	
    	$paginator = null;
    	
    	if(isset($params["conditions"])){
    		$conditions = json_decode($params["conditions"], true);
    		$paginator = $dataTable->getPaginator($conditions);
    	} else {
    		$paginator = $dataTable->getPaginator();
    	}
    		
    	foreach ($paginator as $parcours) {
    		
    		$titre = '';
    		
			$titre = '<a class="href-type-element" href="'
							.$this->url()->fromRoute('parcours/voir', array('id' => $parcours->id)).'">'
							.$escapeHtml($parcours->titre).'
						</a>';
    		
			//$titre = $parcours->titre;
    		
    		
    		$aaData[] = array(
    				$titre,
    				$dataTable->truncate($parcours->description, 250, ' ...', false, true)
    		);
    	}
    	
    	$dataTable->setAaData($aaData);
    
    	if ($this->getRequest()->isXmlHttpRequest()) {
    		return $this->getResponse()->setContent($dataTable->findAll());
    	} else {
    		return new ViewModel();
    	}
    }

    public function ajouterAction()
    {
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $escapeHtml        = $viewHelperManager->get('escapeHtml');
        $form              = new ParcoursForm();
        $Parcours          = new Parcours();
        $request           = $this->getRequest();
        $form->bind($Parcours);

        if ($request->isPost()) {
        	
            $form->setInputFilter($Parcours->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $this->getEntityManager()->persist($Parcours);
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage(sprintf('La Parcours ["%1$s"] a bien été créé.', $escapeHtml($Parcours->titre)));
                return $this->redirect()->toRoute('parcours/voir', array ('id' => $Parcours->id));
            }
            
        }
        
        return new ViewModel(array('form'=>$form));
    }

    /**
     * Affiche la fiche d'un parcours
     * 
     * @return void|\Zend\View\Model\ViewModel
     */
    public function voirAction()
    {

        $id = (int) $this->params('id', null);
        
        if (null === $id) {
            $this->getResponse()->setStatusCode(404);
            return; 
        }

        $Parcours = $this->getEntityManager()->getRepository('Parcours\Entity\Parcours')->findOneBy(array('id'=>$id));
        
        if ($Parcours === null) {
            $this->getResponse()->setStatusCode(404);
            return; 
        }
        
        $SemantiqueTransitions = $this->getEntityManager()
                                      ->getRepository('Parcours\Entity\SemantiqueTransition')
                                      ->findBy(array(), array('semantique'=>'asc'));

        return new ViewModel(array('Parcours'=>$Parcours,'SemantiqueTransitions'=>$SemantiqueTransitions));
    }

    public function modifierAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) 
        {
            $id = (int) $this->params('id', null);
            $Parcours = $this->getEntityManager()
                             ->getRepository('Parcours\Entity\Parcours')
                             ->findOneBy(array('id'=>$id));
            
            if ($Parcours === null || $id === null) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
            
            $request = $this->params()->fromPost();
            
            switch ($request['name']) {
                case 'titre':
                    $Parcours->titre = $request['value'];
                    $this->getEntityManager()->flush();
                    break;
                    
                case 'description':
                    $Parcours->description = $request['value'];
                    $this->getEntityManager()->flush();
                    break;
            
                default:
                    $this->getResponse()->setStatusCode(404);
                    break;
            }
            
            return $this->getResponse()->setContent(Json::encode(true));
        
        } else {
            $this->getResponse()->setStatusCode(404);
        }
    }
    
    /**  
     * Modifie la sémantique ou la narration d'une transition 
     */
    public function modifierTransitionAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) 
        {
            $id = (int) $this->params('id', null);
            $TransitionRecommandee = $this->getEntityManager()
                                          ->getRepository('Parcours\Entity\TransitionRecommandee')
                                          ->findOneBy(array('id'=>$id));
            
            if ($TransitionRecommandee === null || $id === null) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
            
            $request = $this->params()->fromPost();
            switch ($request['name']) {
                case 'semantique':
                    $SemantiqueTransition = $this->getEntityManager()
                                                 ->getRepository('Parcours\Entity\SemantiqueTransition')
                                                 ->findOneBy(array('id'=>$request['value']));
                    $TransitionRecommandee->semantique = $SemantiqueTransition;
                    $this->getEntityManager()->flush();
                    return $this->getResponse()->setContent(Json::encode(array('return'=>$TransitionRecommandee->semantique->semantique)));
                    break;
                    
                case 'narration':
                    $TransitionRecommandee->narration = $request['value'];
                    $this->getEntityManager()->flush();
                    return $this->getResponse()->setContent(Json::encode(true));
                    break;
            
                default:
                    $this->getResponse()->setStatusCode(404);
                    break;
            }
        
        } else {
            $this->getResponse()->setStatusCode(404);
        }
    }

    public function ajouterSousParcoursAction()
    {
        $idsp = (int) $this->params('idsp', null);
        $action = $this->params('type', null);
        if (null === $idsp or null === $action) {
            $this->getResponse()->setStatusCode(404);
            return; 
        }
        $sousparcours = $this->getEntityManager()
                ->getRepository('Parcours\Entity\SousParcours')
                ->findOneBy(array('id'=>$idsp));

        $newsp = new SousParcours();
        $newsp->titre = 'Nouveau sous-parcours';
        $newsp->description = 'Description à écrire';
        $sousparcours->parcours->addSousParcours($newsp);
        $newScene = new SceneRecommandee();
        $newScene->titre = 'Nouvelle scène';
        $newScene->narration = 'Narration à écrire';
        $newsp->addScene($newScene);
        $newsp->scene_depart = $newScene;
        $newTransitionRecommandee = new TransitionRecommandee();
        $newTransitionRecommandee->narration = 'Nouvelle Transition';
        $newsp->parcours->addTransition($newTransitionRecommandee);
        $this->getEntityManager()->persist($newsp);
        $this->getEntityManager()->persist($newScene);
        $this->getEntityManager()->persist($newTransitionRecommandee);
        switch ($action)
        {
            case 'ajAvant': // On ajoute un sous-parcours avant $sousparcours
                $newsp->sous_parcours_suivant = $sousparcours;
                if($sousparcours->parcours->sous_parcours_depart === $sousparcours)
                {
                    $sousparcours->parcours->sous_parcours_depart = $newsp;
                    $newTransitionRecommandee->scene_origine = $newScene;
                    $newTransitionRecommandee->scene_destination = $sousparcours->scene_depart;
                }
                else
                {
                    $tr_before = $this->getEntityManager()
                            ->getRepository('Parcours\Entity\TransitionRecommandee')
                            ->findOneBy(array('scene_destination'=>$sousparcours->scene_depart));
                    $newTransitionRecommandee->scene_origine = $tr_before->scene_origine;
                    $newTransitionRecommandee->scene_destination = $newScene;
                    $sp_before = $tr_before->scene_origine->sous_parcours;
                    $sp_before->sous_parcours_suivant = $newsp;
                    $tr_before->scene_origine = $newScene;
                }
            break;
            case 'ajApres': // On ajoute un sous-parcours après $sousparcours
                $newsp->sous_parcours_suivant = $sousparcours->sous_parcours_suivant;
                $sousparcours->sous_parcours_suivant = $newsp;
                $newTransitionRecommandee->scene_destination = $newScene;
                if($newsp->sous_parcours_suivant === null)
                {
                    foreach ($sousparcours->scenes as $scene)
                    {
                        if($this->getEntityManager()
                            ->getRepository('Parcours\Entity\TransitionRecommandee')
                            ->findOneBy(array('scene_origine'=>$scene))
                            === null)
                        {
                            $last_scene = $scene;
                            break;
                        }
                    }
                    $newTransitionRecommandee->scene_origine = $last_scene;
                }
                else
                {
                    $tr_after = $this->getEntityManager()
                            ->getRepository('Parcours\Entity\TransitionRecommandee')
                            ->findOneBy(array('scene_destination'=>$newsp->sous_parcours_suivant->scene_depart));
                    $newTransitionRecommandee->scene_origine = $tr_after->scene_origine;
                    $tr_after->scene_origine = $newScene;
                }
            break;
        }
        $this->getEntityManager()->flush();
        $this->flashMessenger()->addSuccessMessage(sprintf('Le sous-parcours a bien été ajouté'));
        return $this->redirect()->toRoute('parcours/voir', array('id' => $sousparcours->parcours->id));
    }
}
