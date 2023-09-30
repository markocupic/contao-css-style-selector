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

use Markocupic\ContaoCssStyleSelector\DataContainer\Field\CssStyleSelector;

if (isset($GLOBALS['TL_DCA']['tl_content'])) {
    // This field will be added to the palette by CssStyleSelectorListener::onLoadContent()
    $GLOBALS['TL_DCA']['tl_content']['fields']['cssStyleSelector'] = CssStyleSelector::getFieldConfig();
}
