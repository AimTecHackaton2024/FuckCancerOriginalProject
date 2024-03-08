<?php

namespace Core\Datatype;

use Adminaut\Datatype\File as AdminautFileDatatype;
use Zend\InputFilter\FileInput;

/**
 * Class File
 * @package Adminaut\Datatype
 */
class File extends AdminautFileDatatype
{
    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'type' => FileInput::class,
            'name' => $this->getName(),
            'required' => false,
        ];
    }
}