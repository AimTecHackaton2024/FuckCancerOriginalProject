<?php

namespace Core\Datatype\Select;

use Adminaut\Datatype\DatatypeHelperTrait;
use Adminaut\Datatype\DateTime;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormSelect as ZendFormSelect;

/**
 * Class FormViewHelper
 * @package Adminaut\Datatype\Select
 */
class FormViewHelper extends ZendFormSelect
{
    use DatatypeHelperTrait;

    /**
     * @param ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    /**
     * @param DateTime|ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {

        $this->appendScript('adminaut/themes/adminlte/plugins/select2/select2.full.min.js');
        $this->appendStylesheet('adminaut/themes/adminlte/plugins/select2/select2.css');

        $element->setAttribute('type', 'select');

        $render = parent::render($element);

        $select2Options = [];
        if (isset($element->getOptions()['select2_options'])) {
            $select2Options = $element->getOptions()['select2_options'];
        }

        $render .= '<script>$("select#'. $element->getName() .'").select2('. json_encode($select2Options) .');</script>';

        return $render;
    }
}
