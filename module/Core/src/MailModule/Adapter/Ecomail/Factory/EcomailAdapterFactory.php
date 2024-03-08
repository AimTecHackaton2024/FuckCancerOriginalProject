<?php

namespace MassimoFilippi\MailModule\Adapter\Ecomail\Factory;

use Interop\Container\ContainerInterface;
use MassimoFilippi\MailModule\Adapter\Ecomail\EcomailAdapter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class EcomailAdapterFactory
 * @package MassimoFilippi\MailModule\Adapter\Ecomail\Factory
 */
class EcomailAdapterFactory implements FactoryInterface
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return EcomailAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('Config');

        if (false === isset($config['massimo_filippi']['mail_module']['adapter_params']['api_key'])) {
            throw new ServiceNotCreatedException('Missing adapter parameter: "api_key".');
        }

        $options = [];

        $options['api_key'] = $config['massimo_filippi']['mail_module']['adapter_params']['api_key'];

        return new EcomailAdapter($options);
    }
}
