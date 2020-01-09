<?php

namespace LamBelcebur\MvcBasicTools\Factory\Plugin\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LamBelcebur\MvcBasicTools\Controller\Plugin\AuthenticatePlugin;

class AuthenticatePluginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return AuthenticatePlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthenticatePlugin(
            $container->get(AuthenticationService::class)
        );
    }
}
