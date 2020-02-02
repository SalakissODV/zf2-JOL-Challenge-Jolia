<?php
namespace Challenge\Factory\Service;

use Challenge\Service\ParticipationService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ParticipationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ParticipationService(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}

