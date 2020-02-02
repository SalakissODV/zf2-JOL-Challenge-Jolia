<?php
namespace Challenge\Factory\Service;

use Challenge\Service\ConcoursService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConcoursServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConcoursService(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}

