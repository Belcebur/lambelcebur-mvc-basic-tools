<?php

namespace LamBelcebur\MvcBasicTools;

use Laminas\Authentication\AuthenticationService;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ControllerPluginProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ModuleManager\Feature\ViewHelperProviderInterface;
use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\ServiceLocatorInterface;
use LamBelcebur\MvcBasicTools\Controller\Plugin\AuthenticatePlugin;
use LamBelcebur\MvcBasicTools\Factory\Plugin\Controller\AuthenticatePluginFactory;
use LamBelcebur\MvcBasicTools\Factory\View\Helper\BToolsFactory;
use LamBelcebur\MvcBasicTools\View\Helper\BTools;

class Module implements ConfigProviderInterface, ViewHelperProviderInterface, ControllerPluginProviderInterface, ServiceProviderInterface
{
    public const CONFIG_KEY = __NAMESPACE__;

    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Expected to return \Laminas\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getViewHelperConfig(): array
    {
        return [
            'factories' => [
                BTools::class => BToolsFactory::class,
            ],
            'aliases' => [
                'bTools' => BTools::class,
            ],
        ];
    }

    /**
     * Expected to return \Laminas\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getControllerPluginConfig(): array
    {
        return [
            'factories' => [
                AuthenticatePlugin::class => AuthenticatePluginFactory::class,
            ],
            'aliases' => [
                'authenticatePlugin' => AuthenticatePlugin::class,
            ],
        ];
    }

    /**
     * Expected to return \Laminas\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                AuthenticationService::class => static function (ServiceLocatorInterface $serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ],
        ];
    }
}
