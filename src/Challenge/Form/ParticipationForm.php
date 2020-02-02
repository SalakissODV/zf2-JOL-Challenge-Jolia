<?php
namespace Challenge\Form;

use Jol\User\Service\ReadOnlyUserService;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\Form\Element\File;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class ParticipationForm extends Form {
	public function __construct(ObjectManager $objectManager) {
        parent::__construct('participation_form');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $this->setAttribute('class', 'text-center');

        $this->add([
	        'type'  => File::class,
	        'name' => 'image_p',
	        'options' => [
	            'label' => 'Image',
	        ],
	        'attributes' => [
            	'id' => 'image_p',
                'required' => 'required',
                'accept' => 'image/*',
            ],
	    ]);

        $this->add([
            'name' => 'desc_participation',
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
                    'timeout'   => 1200,
                ],
            ],
        ]);

         $this->add([
            'name' => 'participation_submit',
            'type' => Submit::class,
            'attributes' => [
            	'class' => 'btn btn-success',
                'value' => 'Valider / Validate',
            ],
        ]);


    }
}