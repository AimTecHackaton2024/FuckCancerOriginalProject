<?php declare(strict_types=1);


namespace Frontend\Manager;

use Zend\Filter\StripTags;
use Zend\Form\Element;
use Zend\Form\Form;

abstract class AbstractManager
{
    public function bind($entity, Form $form)
    {
        /* @var $element Element */
        foreach ($form->getElements() as $element) {
            $elementName = $element->getName();

            if (method_exists($element, 'getInsertValue')) {
                $value = $element->getInsertValue();
            } else {
                $value = $element->getValue();
            }

            if (is_string($value)) {
                $value = $this->filterString($value);
            }

            $entity->{$elementName} = $value;
        }
        return $entity;
    }

    private function filterString(string $value): string
    {
        $stripTagsFilter = new StripTags([
            'allowTags' => ['p', 'b', 'strong', 'em', 'i', 'sub', 'sup', 'a'],
        ]);

        return trim($stripTagsFilter->filter($value));
    }
}