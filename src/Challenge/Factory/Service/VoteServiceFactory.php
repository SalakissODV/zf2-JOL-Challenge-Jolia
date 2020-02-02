<?php
namespace Challenge\Factory\Service;

use Challenge\Service\VoteService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class VoteServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new VoteService(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}

