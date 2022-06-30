<?php

/* 
 * @package   [contao-onepage-bundle]
 * @author    Anupam Chatterjee
 * @license   GNU/LGPL
 * @copyright Vrisini Infotech 2022 - 2026
 */

$GLOBALS['TL_DCA']['tl_page']['fields']['hide_in_onepage'] = [
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(',hide,noSearch', ',hide,noSearch,hide_in_onepage', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
