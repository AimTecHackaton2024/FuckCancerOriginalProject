<?php
namespace Frontend;

return [
	'aliases' => [
	],

	'invokables' => [
	],

	'factories' => [
        Manager\OrganizationManager::class => Manager\Factory\OrganizationManagerFactory::class,
        Serializer\OrganizationSerializer::class => Serializer\Factory\OrganizationSerializerFactory::class,
	],
];
