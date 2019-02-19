<?php

namespace JRemmurd\CopyrightAttributionBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use JRemmurd\CopyrightAttributionBundle\Controller\AdminController;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class CreditAuthorsExtension
 * @package JRemmurd\CopyrightAttributionBundle\DependencyInjection
 */
class CopyrightAttributionExtension extends \Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, \Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container
            ->getDefinition(AdminController::class)
            ->setArguments([$mergedConfig["route"], $mergedConfig["subjects"]]);
    }
}