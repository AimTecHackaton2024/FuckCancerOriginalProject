<?php declare(strict_types=1);


namespace Frontend\View\Helper\Factory;

use Frontend\View\Helper\NavLinkViewHelper;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\Mvc\Application;
use Zend\ServiceManager\Factory\FactoryInterface;

class NavLinkViewHelperFactory implements FactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): NavLinkViewHelper
    {
        /** @var Application $application */
        $application = $container->get('Application');

        return new NavLinkViewHelper(
            $application->getMvcEvent()->getRouteMatch()
        );
    }
}