<?php
namespace Challenge\Factory\Controller;

use Challenge\Controller\AjaxController;
use Challenge\Service\ConcoursService;
use Challenge\Service\ParticipationService;
use Profile\Service\UtilisateurService;
use Challenge\Service\VoteService;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use Jol\User\Service\ReadOnlyUserService;

class AjaxControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        return new AjaxController(
            $container->get(ParticipationService::class),
            $container->get(ConcoursService::class),
            $container->get(UtilisateurService::class),
            $container->get(VoteService::class),
            $container->get(ReadOnlyUserService::class)
        );
    }
}