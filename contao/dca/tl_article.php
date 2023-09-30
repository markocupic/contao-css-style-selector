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
use Markocupic\ContaoCssStyleSelector\DataContainer\Field\CssStyleSelector;

if (isset($GLOBALS['TL_DCA']['tl_article'])) {
    if (isset($GLOBALS['TL_DCA']['tl_article']['palettes'])) {
        foreach ($GLOBALS['TL_DCA']['tl_article']['palettes'] as $k => $v) {
            if ($k === '__selector__') {
                continue;
            }

            PaletteManipulator::create()
                ->addField('cssStyleSelector', 'cssID', PaletteManipulator::POSITION_BEFORE)
                ->applyToPalette($k, 'tl_article');
        }
    }

    $GLOBALS['TL_DCA']['tl_article']['fields']['cssStyleSelector'] = CssStyleSelector::getFieldConfig();
}
