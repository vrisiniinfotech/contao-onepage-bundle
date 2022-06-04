<?php

declare(strict_types=1);

/* 
 * @package   [contao-onepage-bundle]
 * @author    Anupam Chatterjee
 * @license   GNU/LGPL
 * @copyright Vrisini Infotech 2022 - 2026
 */

namespace Vrisiniinfotech\ContaoOnepageBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ViOnepageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
