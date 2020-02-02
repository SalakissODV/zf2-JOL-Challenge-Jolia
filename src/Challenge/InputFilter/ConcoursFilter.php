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


class ConcoursFilter extends InputFilter {

	public function __construct() {
		$factory = new InputFactory();

        $this->add($factory->createInput([
            'name' => 'desc_concours',
            'required' => false,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'max' => 2000,
                    ],
                ],
            ],
        ]));

        $this->add($factory->createInput([
            'name' => 'titre_concours',
            'required' => true,
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
        ]));

        $this->add($factory->createInput([
            'name' => 'forum_sujet',
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
        ]));

        $this->add($factory->createInput([
            'name' => 'allowed_users',
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
        ]));

        $this->add($factory->createInput([
            'name' => 'debut_concours',
            'required' => true,
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
        ]));

        $this->add($factory->createInput([
            'name' => 'fin_concours',
            'required' => true,
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
        ]));
	}
}