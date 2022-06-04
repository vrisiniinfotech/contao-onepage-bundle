<?php

declare(strict_types=1);

/* 
 * @package   [contao-onepage-bundle]
 * @author    Anupam Chatterjee
 * @license   GNU/LGPL
 * @copyright Vrisini Infotech 2022 - 2026
 */

namespace Vrisiniinfotech\ContaoOnepageBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Vrisiniinfotech\ContaoOnepageBundle\ViOnepageBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ViOnepageBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
