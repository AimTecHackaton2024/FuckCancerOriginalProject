<?php
namespace Frontend;

return [
    'router' => require_once("router.config.php"),
    'controllers' => require_once("controllers.config.php"),
    'service_manager' => require_once("service-manager.config.php"),
    'view_manager' => require_once("view-manager.config.php"),
    'view_helpers' => require_once("view-helpers.config.php"),
];
