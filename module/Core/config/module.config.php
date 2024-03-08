<?php
namespace Application;

return [
    'doctrine' => require_once("doctrine.config.php"),
    'service_manager' => require_once("service-manager.config.php"),
    'view_helpers' => require_once("view-helpers.config.php"),
];
