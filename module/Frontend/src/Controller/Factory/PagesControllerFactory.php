<?php declare(strict_types=1);


namespace Frontend\Controller\Factory;

use Core\Service\MailService;
use Doctrine\ORM\EntityManager;
use Frontend\Controller\PagesController;
use Frontend\Manager\OrganizationManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PagesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PagesController(
            $container->get(EntityManager::class),
            $container->get(OrganizationManager::class),
            $container->get(MailService::class)
        );
    }

}