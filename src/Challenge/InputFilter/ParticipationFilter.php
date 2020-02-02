<?php

namespace Challenge\InputFilter;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Filter\HtmlEntities;
use Zend\Filter\ToInt;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\Validator\StringLength;


class ParticipationFilter extends InputFilter {

	public function __construct() {
		$factory = new InputFactory();

        $this->add($factory->createInput([
        	'name' => 'image_p',
        	'required' => true,
        ]));
        $this->add($factory->createInput([
            'name' => 'desc_participation',
            'required' => false,
            'filters' => [
                [
                    'name' => StripTags::class,
                ],
                [
                    'name' => HtmlEntities::class,
                ],
                [
                    'name' => StringTrim::class,
                ],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'max' => 900,
                    ],
                ],
            ],
        ]));
	}
}