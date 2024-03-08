<?php declare(strict_types=1);


namespace Core\Service\Factory;

use Adminaut\Service\MailService as AdminautMailService;
use Core\Service\Config;
use Core\Service\MailService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MailService(
            $container->get(AdminautMailService::class),
            $container->get(Config::class)
        );
    }

}