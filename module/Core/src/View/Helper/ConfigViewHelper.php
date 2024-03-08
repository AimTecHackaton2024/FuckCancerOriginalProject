<?php declare(strict_types=1);


namespace Core\View\Helper;

use Core\Service\Config as ConfigService;
use Zend\View\Helper\AbstractHelper;

class ConfigViewHelper extends AbstractHelper
{
    private $config;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;
    }

    public function __invoke(?string $path = null, $default = null)
    {
        return $this->config->get($path, $default);
    }
}