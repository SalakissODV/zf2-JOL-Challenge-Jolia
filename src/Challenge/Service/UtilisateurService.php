<?php
namespace Challenge\Service;

use Jol\Doctrine\Service\AbstractDoctrineObjectService;

class UtilisateurService extends AbstractDoctrineObjectService implements UtilisateurServiceInterface
{
    protected $objectClassName = 'Profile\Entity\Utilisateur';
}

