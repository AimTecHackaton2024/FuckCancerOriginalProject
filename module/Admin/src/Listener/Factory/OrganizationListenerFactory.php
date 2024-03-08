<?php declare(strict_types=1);


namespace Admin\Listener\Factory;

use Admin\Listener\OrganizationListener;
use Core\Service\MailService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class OrganizationListenerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new OrganizationListener(
            $container->get(EntityManager::class),
            $container->get(MailService::class)
        );
    }
}