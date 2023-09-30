<?php

declare(strict_types=1);

/*
 * This file is part of Contao CSS Style Selector.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-css-style-selector
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('cssStyleSelector_legend', 'chmod_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField(['cssStyleSelectorAddClassesToListItem'], 'cssStyleSelector_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings');

$GLOBALS['TL_DCA']['tl_settings']['fields']['cssStyleSelectorAddClassesToListItem'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['cssStyleSelectorAddClassesToListItem'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'sql'       => "int(1) NOT NULL default '0'",
];
