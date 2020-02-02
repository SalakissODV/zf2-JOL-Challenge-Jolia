<?php
namespace Challenge\Service;

use Jol\Doctrine\Service\AbstractDoctrineObjectService;

class VoteService extends AbstractDoctrineObjectService implements VoteServiceInterface {
    protected $objectClassName = 'Challenge\Entity\Vote';
}