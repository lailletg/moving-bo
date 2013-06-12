<?php

namespace Core;

return array(
    'controllers' => array(
        'invokables' => array(
            'typeElement' => 'Collection\Controller\TypeElementController',
            'Collection' => 'Collection\Controller\CollectionController',
            'Artefact' => 'Collection\Controller\ArtefactController',
            'Media' => 'Collection\Controller\MediaController',
            'Semantique' => 'Collection\Controller\SemantiqueController',
            'Relation' => 'Collection\Controller\RelationController',
            'Parcours' => 'Parcours\Controller\ParcoursController',
            'SemantiqueTransition' => 'Parcours\Controller\SemantiqueTransitionController',
            'Scene' => 'Parcours\Controller\SceneController',
        ),
    ),
    'router' => array(
        'routes' => array(
                
            'typeElement' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/typeElement',
                    'defaults' => array(
                        'controller' => 'typeElement',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'add' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/add[/:media-artefact]',
                            'constraints' => array(
                                'media-artefact'     => 'media|artefact',
                            ),
                            'defaults' => array(
                                'controller' => 'typeElement',
                                'action'     => 'add',
                            ),
                        ),
                    ),
                    'editTypeElementAjax' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/editTypeElementAjax[/:id][/:idChamp]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                                'idChamp'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'typeElement',
                                'action'     => 'editTypeElementAjax',
                            ),
                        ),
                    ),
                ),
            ),

            'collection' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/collection',
                    'defaults' => array(
                        'controller' => 'Collection',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'consulter' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/consulter',
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'consulter',
                            ),
                        ),
                    ),
                    'onLine' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/onLine/:id',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'onLine',
                            ),
                        ),
                    ),
                ),
            ),
            
            'artefact' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/artefact',
                    'defaults' => array(
                        'controller' => 'Artefact',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'ajouter' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/ajouter[/:type_element_id]',
                            'constraints' => array(
                                'type_element_id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Artefact',
                                'action'     => 'ajouter',
                            ),
                        ),
                    ),
                    'voirArtefact' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/voir/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Artefact',
                                'action'     => 'voirArtefact',
                            ),
                        ),
                    ),
                    'editArtefact' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit/:id[/:idData]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                                'idData' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Artefact',
                                'action'     => 'editArtefact',
                            ),
                        ),
                    ),
                    'removeArtefact' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/remove/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Artefact',
                                'action'     => 'removeArtefact',
                            ),
                        ),
                    ),
                	'addRelationArtefactSemantique' => array(
                			'type' => 'segment',
                			'options' => array(
                					'route' => '/addRelationArtefactSemantique[/:idDestination][/:idOrigine][/:idSemantique]',
                					'constraints' => array(
                							'idSemantique'  => '[0-9]+',
                							'idDestination' => '[0-9]+',
                							'idOrigine'     => '[0-9]+'
                					),
                					'defaults' => array(
                							'controller' => 'Artefact',
                							'action'     => 'addRelationArtefactSemantique',
                					),
                			),
                	),
                	'supprimerRelationArtefactSemantique' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/supprimerRelationArtefactSemantique/:idRelation',
                			'constraints' => array(
                				'idRelation'  => '[0-9]+'
                			),
                			'defaults' => array(
                				'controller' => 'Artefact',
                				'action'     => 'supprimerRelationArtefactSemantique',
                			),
                		),
                	),
                	'getAllArtefact' => array(
                			'type' => 'Zend\Mvc\Router\Http\Literal',
                			'options' => array(
                					'route' => '/getAllArtefact',
                					'defaults' => array(
                							'controller' => 'Artefact',
                							'action'     => 'getAllArtefact',
                					),
                			),
                	),
                	'addRelationArtefactMedia' => array(
                			'type' => 'segment',
                			'options' => array(
                					'route' => '/addRelationArtefactMedia[/:idMedia]',
                					'constraints' => array(
                							'idMedia' => '[0-9]+'
                					),
                					'defaults' => array(
                							'controller' => 'Artefact',
                							'action'     => 'addRelationArtefactMedia',
                					),
                			),
                	),
                	'getAllMedia' => array(
                			'type' => 'Zend\Mvc\Router\Http\Literal',
                			'options' => array(
                					'route' => '/getAllMedia',
                					'defaults' => array(
                							'controller' => 'Artefact',
                							'action'     => 'getAllMedia',
                					),
                			),
                	),
                ),
            ),

            'media' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/media',
                    'defaults' => array(
                        'controller' => 'Media',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'ajouter' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/ajouter[/:type_element_id]',
                            'constraints' => array(
                                'type_element_id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Media',
                                'action'     => 'ajouter',
                            ),
                        ),
                    ),
                    'voirMedia' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/voir/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Media',
                                'action'     => 'voirMedia',
                            ),
                        ),
                    ),
                    'editMedia' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit/:id[/:idData]',
                            'constraints' => array(
                                'idData'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Media',
                                'action'     => 'editMedia',
                            ),
                        ),
                    ),
                    'removeMedia' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/remove/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Media',
                                'action'     => 'removeMedia',
                            ),
                        ),
                    ),
                	'addRelationMediaArtefact' => array(
                			'type' => 'segment',
                			'options' => array(
                					'route' => '/addRelationMediaArtefact[/:idArtefact]',
                					'constraints' => array(
                							'idArtefact' => '[0-9]+'
                					),
                					'defaults' => array(
                							'controller' => 'Media',
                							'action'     => 'addRelationMediaArtefact',
                					),
                			),
                	),
                	'supprimerRelationMediaArtefact' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/supprimerRelationMediaArtefact/:idMedia/:idArtefact',
                			'constraints' => array(
                				'idArtefact' => '[0-9]+',
                				'idMedia' => '[0-9]+'
                			),
                			'defaults' => array(
                				'controller' => 'Media',
                				'action'     => 'supprimerRelationMediaArtefact',
                			),
                		),
                	),
                	'getAllArtefact' => array(
                			'type' => 'Zend\Mvc\Router\Http\Literal',
                			'options' => array(
                					'route' => '/getAllArtefact',
                					'defaults' => array(
                							'controller' => 'Media',
                							'action'     => 'getAllArtefact',
                					),
                			),
                	),
                ),
            ),
            'semantique' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/semantique',
                    'defaults' => array(
                        'controller' => 'Semantique',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'ajouter' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/ajouter',
                            'defaults' => array(
                                'controller' => 'Semantique',
                                'action'     => 'ajouter',
                            ),
                        ),
                    ),
                    'supprimer' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/supprimer/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Semantique',
                                'action'     => 'supprimer',
                            ),
                        ),
                    ),
                    'modifier' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/modifier/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Semantique',
                                'action'     => 'modifier',
                            ),
                        ),
                    ),
                ),
            ),

            'parcours' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/parcours',
                    'defaults' => array(
                        'controller' => 'Parcours',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'voir' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/voir[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Parcours',
                                'action'     => 'voir',
                            ),
                        ),
                    ),
                	'voirParcourHalviz' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/voirParcourHalviz/:id',
                			'constraints' => array(
                				'id'     => '[0-9]+',
                			),
                			'defaults' => array(
                				'controller' => 'Parcours',
                				'action'     => 'voirParcourHalviz',
                			),
                		),
                	),
                    'ajouter' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/ajouter',
                            'defaults' => array(
                                'controller' => 'Parcours',
                                'action'     => 'ajouter',
                            ),
                        ),
                    ),
                	'supprimer' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/supprimer/:id',
                			'constraints' => array(
                				'id'     => '[0-9]+',
                			),
                			'defaults' => array(
                				'controller' => 'Parcours',
                				'action'     => 'supprimer',
                			),
                		),
                	),
                    'modifierTransition' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/modifierTransition/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Parcours',
                                'action'     => 'modifierTransition',
                            ),
                        ),
                    ),
                	'supprimerTransitionSec' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/supprimerTransitionSec/:id',
                			'constraints' => array(
                				'id'     => '[0-9]+',
                			),
                			'defaults' => array(
                				'controller' => 'Parcours',
                				'action'     => 'supprimerTransitionSec',
                			),
                		),
                	),
                	'ajouterTransitionSec' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/ajouterTransitionSec/:idSceneOrigine[/:idSceneDestination]',
                			'constraints' => array(
                				'idSceneOrigine'     => '[0-9]+',
                				'idSceneDestination' => '[0-9]+',
                				),
                			'defaults' => array(
                				'controller' => 'Parcours',
                				'action'     => 'ajouterTransitionSec',
                			),
                		),
                	),
                    'modifier' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/modifier/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Parcours',
                                'action'     => 'modifier',
                            ),
                        ),
                    ),
                    'voirParcourHalviz' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/voirParcourHalviz[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Parcours',
                                'action'     => 'voirParcourHalviz',
                            ),
                        ),
                    ),
                    'ajouterSousParcours' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/ajouterSousParcours/:type/:idsp',
                            'constraints' => array(
                                'type'     => 'ajAvant|ajApres',
                                'idsp'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Parcours',
                                'action'     => 'ajouterSousParcours',

                            ),
                        ),
                    ),
                	'supprimerSousParcours' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/supprimerSousParcours/:idsp',
                			'constraints' => array(
                				'idsp'     => '[0-9]+',
                			),
                			'defaults' => array(
                				'controller' => 'Parcours',
                				'action'     => 'supprimerSousParcours',
                			),
                		),
                	),
                	'editSousParcours' => array(
                		'type' => 'segment',
                		'options' => array(
                			'route' => '/editSousParcours/:idsp',
                			'constraints' => array(
                				'idsp'     => '[0-9]+',
                			),
                			'defaults' => array(
                				'controller' => 'Parcours',
                				'action'     => 'editSousParcours',
                			),
                		),
                	),
                ),
            ),

            'semantiquetransition' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/semantiquetransition',
                        'defaults' => array(
                            'controller' => 'SemantiqueTransition',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'ajouter' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route' => '/ajouter',
                                'defaults' => array(
                                    'controller' => 'SemantiqueTransition',
                                    'action'     => 'ajouter',
                                ),
                            ),
                        ),
                        'supprimer' => array(
                            'type' => 'segment',
                            'options' => array(
                                'route' => '/supprimer/:id',
                                'constraints' => array(
                                    'id'     => '[0-9]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'SemantiqueTransition',
                                    'action'     => 'supprimer',
                                ),
                            ),
                        ),
                        'modifier' => array(
                            'type' => 'segment',
                            'options' => array(
                                'route' => '/modifier/:id',
                                'constraints' => array(
                                    'id'     => '[0-9]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'SemantiqueTransition',
                                    'action'     => 'modifier',
                                ),
                            ),
                        ),
                    ),
                ),

            'scene' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/scene',
                    'defaults' => array(
                        'controller' => 'Scene',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'ajouterScene' => array(
                            'type' => 'segment',
                            'options' => array(
                                'route' => '/ajouter/:type/:id',
                                'constraints' => array(
                                    'type'     => 'ajAvant|ajApres',
                                    'id'     => '[0-9]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'Scene',
                                    'action'     => 'ajouterScene',
                                ),
                            ),
                        ),
                    'voirScene' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/voir/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Scene',
                                'action'     => 'voirScene',
                            ),
                        ),
                    ),
                    'removeScene' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/remove/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Scene',
                                'action'     => 'removeScene',
                            ),
                        ),
                    ),
                    'editScene' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Scene',
                                'action'     => 'editScene',
                            ),
                        ),
                    ),
                    'deleteElement' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/deleteElement/:idScene/:idElement',
                            'constraints' => array(
                                'idScene'     => '[0-9]+',
                                'idElement'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Scene',
                                'action'     => 'deleteElement',
                            ),
                        ),
                    ),
                	'getAllElement' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                				'route' => '/getAllElement',
                				'defaults' => array(
                						'controller' => 'Scene',
                						'action'     => 'getAllElement',
                				),
                		),
                	),
                	'addRelationSceneElement' => array(
                		'type' => 'segment',
                		'options' => array(
                				'route' => '/addRelationSceneElement[/:idElement]',
                				'constraints' => array(
                						'idElement' => '[0-9]+'
                				),
                				'defaults' => array(
                						'controller' => 'Scene',
                						'action'     => 'addRelationSceneElement',
                				),
                		),
                	),
                ),
            ),
        ),
    ),
        
    'view_manager' => array(
        'template_map' => array(
            
            'collection/collection/consulter'    => __DIR__ . '/../view/Collection/Collection/consulter.phtml',
                
            'collection/artefact/ajouter'        => __DIR__ . '/../view/Collection/Artefact/ajouter.phtml',
            'collection/artefact/edit-artefact'  => __DIR__ . '/../view/Collection/Artefact/edit-artefact.phtml',
            'collection/artefact/voir-artefact'  => __DIR__ . '/../view/Collection/Artefact/voir-artefact.phtml',

            'collection/media/ajouter'           => __DIR__ . '/../view/Collection/Media/ajouter.phtml',
            'collection/media/edit-media'        => __DIR__ . '/../view/Collection/Media/edit-media.phtml',
            'collection/media/voir-media'        => __DIR__ . '/../view/Collection/Media/voir-media.phtml',
                
            'collection/semantique/index'        => __DIR__ . '/../view/Collection/Semantique/index.phtml',
            'collection/semantique/ajouter'      => __DIR__ . '/../view/Collection/Semantique/ajouter.phtml',
            'collection/semantique/modifier'     => __DIR__ . '/../view/Collection/Semantique/modifier.phtml',
                
            'collection/type-element/index'      => __DIR__ . '/../view/Collection/Type-Element/index.phtml',
            'collection/type-element/add'        => __DIR__ . '/../view/Collection/Type-Element/add.phtml',
        		
        	'parcours/semantique-transition/index'	=> __DIR__ . '/../view/Parcours/Semantique-Transition/index.phtml',
        	'parcours/semantique-transition/ajouter'=> __DIR__ . '/../view/Parcours/Semantique-Transition/ajouter.phtml',
        		
        	'parcours/parcours/index'		=> __DIR__ . '/../view/Parcours/Parcours/index.phtml',
        	'parcours/parcours/voir'		=> __DIR__ . '/../view/Parcours/Parcours/voir.phtml',
        	'parcours/parcours/ajouter'		=> __DIR__ . '/../view/Parcours/Parcours/ajouter.phtml',
        		
        	'parcours/scene/voir-scene'		=> __DIR__ . '/../view/Parcours/Scene/voir-scene.phtml',
        	'parcours/scene/edit-scene'		=> __DIR__ . '/../view/Parcours/Scene/edit-scene.phtml',
        ),
        'template_path_stack' => array(
            'Collection' => __DIR__ . '/../view',
        )
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            'Core_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                        __DIR__ . '/../src/Collection/Entity',
                        __DIR__ . '/../src/Parcours/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Collection\Entity' => 'Core_driver',
                    'Parcours\Entity' => 'Core_driver',
                )
            )
        )
    ),
    'data-fixture' => array(
            'Collection_fixture' => __DIR__ . '/../src/Collection/Fixture',
            'Parcours_fixture' => __DIR__ . '/../src/Parcours/Fixture'
    ),

);
