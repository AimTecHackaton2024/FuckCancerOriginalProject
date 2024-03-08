<?php declare(strict_types=1);


namespace Frontend\Form\View\Helper;

use Adminaut\Datatype\View\Helper\FormRow as AdminautFormRow;
use TwbBundle\Form\View\Helper\TwbBundleForm;
use Zend\Form\Element\Button;
use Zend\Form\Element\Submit;
use Zend\Form\ElementInterface;
use Zend\Form\LabelAwareInterface;

class FormRow extends AdminautFormRow
{
    protected function renderElement(ElementInterface $oElement, $sLabelPosition = null)
    {
        //Retrieve expected layout
        $sLayout = $oElement->getOption('twb-layout');

        // Define label position
        if ($sLabelPosition === null) {
            $sLabelPosition = $this->getLabelPosition();
        }

        //Render label
        $sLabelOpen = $sLabelClose = $sLabelContent = $sElementType = '';
        if ($sLabelContent = $this->renderLabel($oElement)) {
            /*
             * Multicheckbox elements have to be handled differently
             * as the HTML standard does not allow nested labels.
             * The semantic way is to group them inside a fieldset
             */
            $sElementType = $oElement->getAttribute('type');

            //Button element is a special case, because label is always rendered inside it
            if (($oElement instanceof Button) or ( $oElement instanceof Submit)) {
                $sLabelContent = '';
            } else {
                $aLabelAttributes = $oElement->getLabelAttributes() ? : $this->labelAttributes;

                //Validation state
                if ($oElement->getOption('validation-state') || $oElement->getMessages()) {
                    if (empty($aLabelAttributes['class'])) {
                        $aLabelAttributes['class'] = 'control-label';
                    } elseif (!preg_match('/(\s|^)control-label(\s|$)/', $aLabelAttributes['class']) && $sElementType !== 'checkbox') {
                        $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' control-label');
                    }
                }

                $oLabelHelper = $this->getLabelHelper();
                switch ($sLayout) {
                    //Hide label for "inline" layout
                    case TwbBundleForm::LAYOUT_INLINE:
                        if ($sElementType !== 'checkbox') {
                            if ($sElementType !== 'checkbox') {
                                if (empty($aLabelAttributes['class']) && empty($oElement->getOption('showLabel'))) {
                                    $aLabelAttributes['class'] = 'sr-only';
                                } elseif (empty($oElement->getOption('showLabel')) && !preg_match('/(\s|^)sr-only(\s|$)/', $aLabelAttributes['class'])) {
                                    $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' sr-only');
                                }
                            }
                        }
                        break;

                    case TwbBundleForm::LAYOUT_HORIZONTAL:
                        if ($sElementType !== 'checkbox') {
                            if (empty($aLabelAttributes['class'])) {
                                $aLabelAttributes['class'] = 'control-label';
                            } else {
                                if (!preg_match('/(\s|^)control-label(\s|$)/', $aLabelAttributes['class'])) {
                                    $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' control-label');
                                }
                            }
                        }
                        break;
                }
                if ($aLabelAttributes) {
                    $oElement->setLabelAttributes($aLabelAttributes);
                }

                $sLabelOpen = $oLabelHelper->openTag($oElement->getAttribute('id') ? $oElement : $aLabelAttributes);
                $sLabelClose = $oLabelHelper->closeTag();

                // Allow label html escape desable
                //$sLabelContent = $this->getEscapeHtmlHelper()->__invoke($sLabelContent);

                if (!$oElement instanceof LabelAwareInterface || !$oElement->getLabelOption('disable_html_escape')) {
                    $sLabelContent = $this->getEscapeHtmlHelper()->__invoke($sLabelContent);
                }
            }
        }

        //Add required string if element is required
        if ($this->requiredFormat &&
            $oElement->getAttribute('required') &&
            strpos($this->requiredFormat, $sLabelContent) === false
        ) {
            $sLabelContent .= $this->requiredFormat;
        }

        switch ($sLayout) {
            case null:
            case TwbBundleForm::LAYOUT_INLINE:

                $sElementContent = $this->getElementHelper()->render($oElement);

                // Checkbox elements are a special case, element is already rendered into label
                if ($sElementType === 'checkbox') {
                    $sElementContent = sprintf(static::$checkboxFormat, $sElementContent);
                    $sElementContent .= $this->renderHelpBlock($oElement);
                } else {
                    if ($sLabelPosition === self::LABEL_PREPEND) {
                        $sElementContent = $sLabelOpen . $sLabelContent . $sLabelClose . $this->renderHelpBlock($oElement) . $sElementContent;
                    } else {
                        $sElementContent = $sElementContent . $sLabelOpen . $sLabelContent . $sLabelClose . $this->renderHelpBlock($oElement);
                    }
                }

                //Render errors
                if ($this->renderErrors) {
                    $sElementContent .= $this->getElementErrorsHelper()->render($oElement);
                }

                return $sElementContent;

            case TwbBundleForm::LAYOUT_HORIZONTAL:
                $sElementContent = $this->getElementHelper()->render($oElement);

                //Render errors
                if ($this->renderErrors) {
                    $sElementContent .= $this->getElementErrorsHelper()->render($oElement);
                }

                $sClass = '';

                //Column size
                if ($sColumSize = $oElement->getOption('column-size')) {
                    $sClass .= ' col-' . $sColumSize;
                }

                // Checkbox elements are a special case, element is rendered into label
                if ($sElementType === 'checkbox') {
                    return sprintf(
                        static::$horizontalLayoutFormat, $sClass, sprintf(static::$checkboxFormat, $sElementContent)
                    );
                }

                if ($sLabelPosition === self::LABEL_PREPEND) {
                    return $sLabelOpen . $sLabelContent . $sLabelClose . $this->renderHelpBlock($oElement) . sprintf(
                            static::$horizontalLayoutFormat, $sClass, $sElementContent
                        );
                } else {
                    return sprintf(
                            static::$horizontalLayoutFormat, $sClass, $sElementContent
                        ) . $sLabelOpen . $sLabelContent . $sLabelClose . $this->renderHelpBlock($oElement);
                }
        }
        throw new DomainException('Layout "' . $sLayout . '" is not valid');
    }
}