<?php

namespace Nadia\Bundle\FrameworkExtraBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NadiaFrameworkExtraExtension
 */
class NadiaFrameworkExtraExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $configFilesToLoad = [];

        if ($config['view_switch']['annotations']) {
            $configFilesToLoad[] = 'view_switch.yml';
        }

        if (count($configFilesToLoad)) {
            foreach ($configFilesToLoad as $configFile) {
                $loader->load($configFile);
            }

            if (!$container->hasDefinition('sensio_framework_extra.controller.listener')) {
                $loader->load('annotations.yml');
            }
        }
    }
}
