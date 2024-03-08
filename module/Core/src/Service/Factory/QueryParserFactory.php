<?php declare(strict_types=1);


namespace Core\Service\Factory;

use Core\Entity\OrganizationCategory;
use Core\Service\QueryParser;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class QueryParserFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new QueryParser(
            $entityManager->getRepository(OrganizationCategory::class)
        );
    }

}