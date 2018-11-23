<?php

namespace App\Elective\FormatterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class ElectiveFormatterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (isset($configs[0]['response']['formatter']['headers'])) {
            $container->setParameter('elective_formatter.response.formatter.headers', $configs[0]['response']['formatter']['headers']);
        } else {
            $container->setParameter('elective_formatter.response.formatter.headers', array());
        }
    }
}
