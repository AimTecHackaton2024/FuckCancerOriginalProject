<?php declare(strict_types=1);


namespace Frontend\Serializer\Factory;

use Adminaut\Manager\FileManager;
use Frontend\Serializer\OrganizationSerializer;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class OrganizationSerializerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new OrganizationSerializer(
            $container->get(FileManager::class)
        );
    }
}