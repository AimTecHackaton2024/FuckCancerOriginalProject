<?php declare(strict_types=1);


namespace Frontend\Controller\Factory;

use Core\Entity\Organization;
use Core\Entity\OrganizationCategory;
use Core\Service\QueryParser;
use Doctrine\ORM\EntityManager;
use Frontend\Controller\MapController;
use Frontend\Serializer\OrganizationSerializer;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MapControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new MapController(
            $entityManager->getRepository(Organization::class),
            $entityManager->getRepository(OrganizationCategory::class),
            $container->get(OrganizationSerializer::class),
            $container->get(QueryParser::class)
        );
    }
}