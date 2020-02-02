<?php
namespace Challenge\Factory\Form;

use Challenge\Form\ParticipationForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ParticipationFormFactory implements FactoryInterface {
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new ParticipationForm(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}