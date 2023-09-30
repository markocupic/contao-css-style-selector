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

$GLOBALS['BE_MOD']['design']['cssStyleSelector'] = [
    'tables'     => [
        'tl_css_style_selector',
        'tl_css_style_selector_group',
    ],
    'stylesheet' => ['bundles/markocupiccontaocssstyleselector/style.css|static'],
];
