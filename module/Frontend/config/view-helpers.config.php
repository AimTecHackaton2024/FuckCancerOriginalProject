<?php
namespace Frontend;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
	'aliases' => [
        'navLink' => View\Helper\NavLinkViewHelper::class,
        'fileSize' => View\Helper\FileSizeHelper::class,
	],

	'invokables' => [
        View\Helper\FileSizeHelper::class,


//        'formrow' => Form\View\Helper\FormRow::class,
//        'form_row' => Form\View\Helper\FormRow::class,
//        'formRow' => Form\View\Helper\FormRow::class,
//        'FormRow' => Form\View\Helper\FormRow::class,
	],

	'factories' => [
        View\Helper\NavLinkViewHelper::class => View\Helper\Factory\NavLinkViewHelperFactory::class,
	],
];
