<?php
namespace Challenge;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use ZfcRbac\Guard\RouteGuard;

return [
    'controllers' => [
        'factories' => [
            'Challenge\Controller\Challenge' => Factory\Controller\ChallengeControllerFactory::class,
            'Challenge\Controller\Admin' => Factory\Controller\AdminControllerFactory::class,
            'Challenge\Controller\Ajax' => Factory\Controller\AjaxControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\ConcoursService::class => Factory\Service\ConcoursServiceFactory::class,
            Service\ParticipationService::class => Factory\Service\ParticipationServiceFactory::class,
            Service\UtilisateurService::class => Factory\Service\UtilisateurServiceFactory::class,
            Service\VoteService::class => Factory\Service\VoteServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\ParticipationForm::class => Factory\Form\ParticipationFormFactory::class,
            Form\ConcoursForm::class => Factory\Form\ConcoursFormFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'challenge' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/challenge-jolia',
                    'defaults' => [
                        '__NAMESPACE__' => Controller::class,
                        'controller' => 'Challenge',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                	'listConcours' => [
                        'type' => 'literal',
                        'options' => [
                            'route'    => '/listConcours',
                            'defaults' => [
                                'controller' => 'Challenge',
                                'action'     => 'listConcours',
                            ],
                        ],
                    ],
                    'help' => [
                        'type' => 'literal',
                        'options' => [
                            'route'    => '/help',
                            'defaults' => [
                                'controller' => 'Challenge',
                                'action'     => 'help',
                            ],
                        ],
                    ],
                    'participer' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/participer/:id',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => 'Challenge',
                                'action' => 'participer',
                            ],
                        ],
                    ],
                    'voir' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/voir/:id',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => 'Challenge',
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'admin' => [
                       'type' => 'literal',
                        'options' => [
                            'route' => '/admin',
                            'defaults' => [
                                'controller' => 'Admin',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'addConcours' => [
                                'type' => 'literal',
                                'options' => [
                                    'route'    => '/addConcours',
                                    'defaults' => [
                                        'controller' => 'Admin',
                                        'action'     => 'addConcours',
                                    ],
                                ],
                            ],
                            'editConcours' => [
                                'type' => 'segment',
                                'options' => [
                                    'route'    => '/editConcours/:id',
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin',
                                        'action'     => 'editConcours',
                                    ],
                                ],
                            ],
                            'showConcours' => [
                                'type' => 'segment',
                                'options' => [
                                    'route'    => '/showConcours/:id',
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin',
                                        'action'     => 'showConcours',
                                    ],
                                ],
                            ],
                            'voteConcours' => [
                                'type' => 'segment',
                                'options' => [
                                    'route'    => '/voteConcours/:id',
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin',
                                        'action'     => 'voteConcours',
                                    ],
                                ],
                            ],
                            'resultConcours' => [
                                'type' => 'segment',
                                'options' => [
                                    'route'    => '/resultConcours/:id',
                                    'constraints' => [
                                        'id' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => 'Admin',
                                        'action'     => 'resultConcours',
                                    ],
                                ],
                            ],
                            'delConcours' => [
                                'type' => 'literal',
                                'options' => [
                                    'route'    => '/delConcours',
                                    'defaults' => [
                                        'controller' => 'Ajax',
                                        'action'     => 'delConcours',
                                    ],
                                ],
                            ],
                            'delParticipation' => [
                                'type' => 'literal',
                                'options' => [
                                    'route'    => '/delParticipation',
                                    'defaults' => [
                                        'controller' => 'Ajax',
                                        'action'     => 'delParticipation',
                                    ],
                                ],
                            ],
                            'addVote' => [
                                'type' => 'literal',
                                'options' => [
                                    'route'    => '/addVote',
                                    'defaults' => [
                                        'controller' => 'Ajax',
                                        'action'     => 'addVote',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
    'doctrine' => [
        'driver' => [
            'ChallengeAnnotationDriver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Challenge/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    Entity::class => 'ChallengeAnnotationDriver',
                ],
            ],
        ],
    ],
    'jol' => [
        'challenge' => [
            'widgets' => [
                'square_ad' => [
                    'enabled' => true,
                ],
            ],
        ],
    ],
     'zfc_rbac' => [
        'guards' => [
            RouteGuard::class => [
                'challenge/admin' => ['team'],
                'challenge/admin/showConcours' => ['team'],
                'challenge/admin/addConcours' => ['team'],
                'challenge/admin/editConcours' => ['team'],
                'challenge/admin/delConcours' => ['team'],
                'challenge/admin/delParticipation' => ['team'],
            ],
        ],
    ],
];