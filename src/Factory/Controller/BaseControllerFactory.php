<?php

namespace LamBelcebur\MvcBasicTools\Factory\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\Mvc\I18n\Translator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use LamBelcebur\MvcBasicTools\Controller\BaseController;

class BaseControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     *
     * @return BaseController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(EntityManager::class),
            $container->get(Translator::class),
            $container->get('Router')
        );
    }
}
