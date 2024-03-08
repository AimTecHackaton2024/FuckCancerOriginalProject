<?php
namespace Admin;

return [
	'aliases' => [
	],

	'invokables' => [
	],

	'factories' => [
        Listener\OrganizationListener::class => Listener\Factory\OrganizationListenerFactory::class
	],
];
