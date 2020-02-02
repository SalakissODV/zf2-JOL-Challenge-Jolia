<?php

namespace Challenge\Factory\Service;



use Challenge\Service\UtilisateurService;

use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;



class UtilisateurServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)

    {

        return new UtilisateurService(

            $container->get('doctrine.entitymanager.orm_default')

        );

    }

}