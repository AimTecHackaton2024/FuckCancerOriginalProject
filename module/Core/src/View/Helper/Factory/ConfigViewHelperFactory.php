<?php declare(strict_types=1);


namespace Core\View\Helper\Factory;

use Core\Service\Config;
use Core\View\Helper\ConfigViewHelper;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConfigViewHelperFactory implements FactoryInterface
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ConfigViewHelper
    {
        return new ConfigViewHelper(
            $container->get(Config::class)
        );
    }
}