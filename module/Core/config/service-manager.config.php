<?php
namespace Core;

use Adminaut\Manager\FileManager;
use Adminaut\Service\MailService as AdminautMailService;

return [
	'aliases' => [
	],

	'invokables' => [
	],

	'factories' => [
        // Adminaut

        FileManager::class => Adminaut\Manager\Factory\FileManagerFactory::class,
        AdminautMailService::class => Adminaut\Service\Factory\MailServiceFactory::class,

        // --------

        Service\Config::class => Service\Factory\ConfigFactory::class,
        Service\QueryParser::class => Service\Factory\QueryParserFactory::class,
        Service\MailService::class => Service\Factory\MailServiceFactory::class,
	],
];
