<?php
namespace Frontend;

return [
	'display_not_found_reason' => true,
	'display_exceptions' => true,
	'doctype' => 'HTML5',
	'not_found_template' => 'frontend/error/404',
	'exception_template' => 'frontend/error/pages',
	'template_map' => [
	    'frontend/layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
	    'frontend/error/404' => __DIR__ . '/../view/error/404.phtml',
	    'frontend/error/pages' => __DIR__ . '/../view/error/index.phtml',
	],
	'template_path_stack' => [
	    __DIR__ . '/../view',
	],
];
