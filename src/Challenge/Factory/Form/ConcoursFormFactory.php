<?php
namespace Challenge\Factory\Form;

use Challenge\Form\ConcoursForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConcoursFormFactory implements FactoryInterface {
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new ConcoursForm(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}