<?php
namespace Challenge\Form;

use Jol\User\Service\ReadOnlyUserService;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class ConcoursForm extends Form {
	public function __construct(ObjectManager $objectManager) {
        parent::__construct('concours_form');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $this->setAttribute('class', 'text-center');

        $this->add([
            'name' => 'titre_concours',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'forum_sujet',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'allowed_users',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'choix_jeu',
            'type' => Select::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'choix_jeu'
            ],
            'options' => [
                'disable_inarray_validator' => false,
                'empty_option' => '** Choisir un jeu **',
                'value_options' => [
                    '0' => 'Dofus PC',
                    '1' => 'Dofus Touch',
                    '2' => 'Dofus PC et Dofus Touch',
                ],
            ]
        ]);

        $this->add([
            'name' => 'voir_participations',
            'type' => Select::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'voir_participations'
            ],
            'options' => [
                'disable_inarray_validator' => true,
                'empty_option' => '** Affichage des participations **',
                'value_options' => [
                    '0' => 'Masquer les participations jusqu\'aux votes.',
                    '1' => 'Afficher les participations à tous.',
                ],
            ]
        ]);

        $this->add([
            'name' => 'debut_concours',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control debut_concours',
            ],
        ]);

        $this->add([
            'name' => 'fin_concours',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control fin_concours',
            ],
        ]);

        $this->add([
            'name' => 'desc_concours',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description',
            ],
            'attributes' => [
                'class' => 'form-control',
                'rows' => 5,
                'cols' => 15,
            ],
        ]);

        $this->add([
            'name' => 'security',
            'type' => Csrf::class,
            'options' => [
                'csrf_options' => [
                    'timeout'   => 600,
                ],
            ],
        ]);

         $this->add([
            'name' => 'concours_submit',
            'type' => Submit::class,
            'attributes' => [
            	'class' => 'btn btn-success',
                'value' => 'Valider',
            ],
        ]);


    }
}