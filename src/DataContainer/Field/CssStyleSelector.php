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

namespace Markocupic\ContaoCssStyleSelector\DataContainer\Field;

class CssStyleSelector
{
    public static function getFieldConfig(): array
    {
        return [
            'label'     => &$GLOBALS['TL_LANG']['MSC']['cssStyleSelector'],
            'exclude'   => true,
            'inputType' => 'select',
            'search'    => true,
            'eval'      => ['chosen' => true, 'multiple' => true, 'tl_class' => 'clr'],
            'sql'       => 'blob NULL',
        ];
    }
}
