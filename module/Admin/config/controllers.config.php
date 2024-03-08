<?php
namespace Admin;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
	'aliases' => [
	],

	'invocables' => [
	],

	'factories' => [
        Controller\SettingsController::class => Controller\Factory\SettingsControllerFactory::class,
	],
];
