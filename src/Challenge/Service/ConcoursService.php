<?php
namespace Challenge\Service;

use Jol\Doctrine\Service\AbstractDoctrineObjectService;

class ConcoursService extends AbstractDoctrineObjectService implements ConcoursServiceInterface
{
    protected $objectClassName = 'Challenge\Entity\Concours';
}

