<?php declare(strict_types=1);


namespace Frontend\Manager\Factory;

use Adminaut\Manager\FileManager;
use Doctrine\ORM\EntityManager;
use Frontend\Manager\OrganizationManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class OrganizationManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new OrganizationManager(
            $container->get(FileManager::class),
            $container->get(EntityManager::class)
        );
    }

}