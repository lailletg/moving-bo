<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use SamUser\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\View\Helper\Notification;
use Application\View\Helper\demandeRole;
use Application\View\Helper\redirectUserIndexIfTrue;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Validator\Digits;

use Gedmo\Loggable\LoggableListener as LoggableListener;
//use Application\Library\TablePrefix;

class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $cache = new \Doctrine\Common\Cache\ArrayCache;
        // standard annotation reader
        $annotationReader = new \Doctrine\Common\Annotations\AnnotationReader;

        $sm = $e->getApplication()->getServiceManager();
        $em = $sm->get('doctrine.entitymanager.orm_default');
        $evm = $em->getEventManager();

        
        //$tablePrefix = new TablePrefix('mbo_');
        //$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);
      	
      	//  $evm = new \Doctrine\Common\EventManager();
        $auth = $sm->get('zfcuser_auth_service');

        $user = ($auth->getIdentity()) ? $auth->getIdentity() : 'DEV' ;
        
        $loggableListener = new LoggableListener;
        //$loggableListener->setAnnotationReader($cachedAnnotationReader);
        $loggableListener->setUsername($user);
        
        $evm->addEventSubscriber($loggableListener);

        
        $events = $e->getApplication()->getEventManager()->getSharedManager();

        $events->attach('ZfcUser\Form\Register','init', function($e) {
        	$form = $e->getTarget();
        	
        	$form->add(array(
        			'name' => 'checkboxAgreement',
        			'type' => 'Zend\Form\Element\Checkbox',
        			'options' => array(
        					//'label' => "J'accepte les <a href='".$this->url('page/modifier',array('slug'=>'conditions-generales'))."'>conditions générales</a> du projet CERVIN",
        					'label' => 'J\'accepte les conditions générales du projet CERVIN',
        					'use_hidden_element' => true,
        					'checked_value' => 1,
                     		'unchecked_value' => 'no'
        			)
        	));
        	
        });
        
        $events->attach('ZfcUser\Form\RegisterFilter','init', function($e) {
        	$filter = $e->getTarget();
        		
    		/*$filter->add(array(
    				'name' => 'checkboxAgreement',
    				'validators' => array(
    						array(
    								'name' => 'Identical',
    								'options' => array(
    										'token' => true,
    										'messages' => array(
    												\Zend\Validator\Identical::NOT_SAME => 'Vous devez accepter les conditions générales',
    										),
    								),
    						),
    				),
    		));*/
        	
        	$filter->add(array(
        			'name'     => 'checkboxAgreement',
        			'required'   => true,
        			'allowEmpty' => false,
        			'validators' => array(
        					array(
        							'name' => 'Digits',
        							'break_chain_on_failure' => true,
        							'options' => array(
        									'messages' => array(
        											Digits::NOT_DIGITS   => 'You must agree to the terms of use.',
        									),
        							),
        					),
        			),
        	));
    		
        });
        
        $zfcServiceEvents  = $sm->get('zfcuser_user_service')->getEventManager();
        
        $zfcServiceEvents->attach('register', function($e) {
        	$form = $e->getParam('form');
        	//$user = $e->getParam('user');
        });
        
        // adding action for user login
        $zfcServiceEvents->attach('authenticate.post', function($e){
        			$user = $e->getParam('user');  // User account object
        			$id = $user->getId(); // get user id
        			\Doctrine\Common\Util\Debug::dump($user);
					
        		}
        );
        
        /*$translator = new Translator();
        $translator->addTranslationFile(
         'phpArray',
         './vendor/zendframework/zendframework/resources/languages/fr/Zend_Validate.php',
         'default',
         'fr_FR'
        );
        $translate = new \Zend\I18n\Translator\Translator();
        AbstractValidator::setDefaultTranslator($translator);*/
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                // the array key here is the name you will call the view helper by in your view scripts
                /*'adminEmail' => function($sm) {
                    $locator = $sm->getServiceLocator(); // $sm is the view helper manager, so we need to fetch the main service manager
                    return new adminEmail($locator->get('Doctrine\ORM\EntityManager'));
                },*/
                'Notification' => function ($helperPluginManager) {
                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $viewHelper = new Notification();
                    $viewHelper->setServiceLocator($serviceLocator);
                    return $viewHelper;
                },
                'demandeRole' => function ($helperPluginManager) {
                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $viewHelper = new demandeRole();
                    $viewHelper->setServiceLocator($serviceLocator);
                    return $viewHelper;
                },
                'flashMessages' => function($sm) {
                    $flashmessenger = $sm->getServiceLocator()
                        ->get('ControllerPluginManager')
                        ->get('flashmessenger');
 
                    $messages = new \Application\View\Helper\FlashMessages();
                    $messages->setFlashMessenger($flashmessenger);
 
                    return $messages;
                }
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'SamUser' => __DIR__ . '/src/SamUser',
                    'Admin' => __DIR__ . '/src/Admin',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'zfcuser_user_service' => 'SamUser\Service\User2',
            ),
            'factories' => array(

            )
        );
    }
}
