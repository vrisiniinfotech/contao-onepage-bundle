<?php

/* 
 * @package   [contao-onepage-bundle]
 * @author    Anupam Chatterjee
 * @license   GNU/LGPL
 * @copyright Vrisini Infotech 2022 - 2026
 */

use Vrisiniinfotech\ContaoOnepageBundle\Hooks\OnePageAssorter;
/**
 * Hook to assort contents from all pages for one-page website
 */
$GLOBALS['TL_HOOKS']['getArticles'][] = array(OnePageAssorter::class, 'getMyArticles');
$GLOBALS['TL_HOOKS']['parseTemplate'][] = array(OnePageAssorter::class, 'replaceNavTemplates');
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array(OnePageAssorter::class, 'getSearchablePages');
