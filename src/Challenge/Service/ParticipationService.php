<?php
namespace Challenge\Service;

use Jol\Doctrine\Service\AbstractDoctrineObjectService;

class ParticipationService extends AbstractDoctrineObjectService implements ParticipationServiceInterface
{
    protected $objectClassName = 'Challenge\Entity\Participation';
}

