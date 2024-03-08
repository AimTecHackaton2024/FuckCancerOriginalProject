<?php


namespace Admin\Controller\Factory;


use Admin\Controller\SettingsController;
use Adminaut\Manager\FileManager;
use Adminaut\Manager\ModuleManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\Renderer\PhpRenderer;

class SettingsControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SettingsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SettingsController(
            $container->get(PhpRenderer::class),
            $container->get(ModuleManager::class),
            $container->get(FileManager::class)
        );
    }
}