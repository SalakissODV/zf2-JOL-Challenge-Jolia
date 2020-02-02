<?php
namespace Challenge\Factory\Controller;

use Challenge\Controller\AdminController;
use Challenge\Service\ConcoursService;
use Challenge\Service\ParticipationService;
use Profile\Service\UtilisateurService;
use Challenge\Service\VoteService;
use Challenge\Form\ConcoursForm;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use Jol\User\Service\ReadOnlyUserService;

class AdminControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new AdminController(
        	$container->get('FormElementManager')->get(ConcoursForm::class),
            $container->get(ParticipationService::class),
            $container->get(ConcoursService::class),
            $container->get(UtilisateurService::class),
            $container->get(VoteService::class),
            $container->get(ReadOnlyUserService::class)
        );
    }
}