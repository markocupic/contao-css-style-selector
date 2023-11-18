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

namespace Markocupic\ContaoCssStyleSelector\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\DataContainer;
use Symfony\Component\HttpFoundation\RequestStack;

class CssStyleSelectorInternalListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
    ) {
    }

    #[AsCallback(table: 'tl_css_style_selector', target: 'list.label.label')]
    public function labelCallback(array $row, string $label, DataContainer $dc, array $args): string
    {
        $fieldNames = [
            'disableInPage',
            'disableInArticle',
            'disableInContent',
            'disableInForm',
            'disableInFormField',
            'disableInLayout',
            'disableInModule',
        ];

        $html = '';

        foreach ($fieldNames as $index => $fieldName) {
            $argIndex = $index + 2;
            $args[$argIndex] = $GLOBALS['TL_LANG']['MSC'][($row[$fieldName] ? 'no' : 'yes')];
            $html .= '<td class="tl_file_list">'.$args[$argIndex].'</td>';
        }

        return $label.'</td>'
            .'<td class="tl_file_list" style="min-width: 0 !important; padding-left: 0 !important; padding-right: 0 !important; width: 0 !important;"></td>'
            .$html;
    }

    #[AsCallback(table: 'tl_css_style_selector', target: 'list.label.group')]
    public function groupCallback(string|null $group, string|null $mode, string|null $field, array|null $recordData, DataContainer|null $dc): string
    {
        $fieldNames = [
            'pageEnabled',
            'articleEnabled',
            'contentEnabled',
            'formEnabled',
            'formFieldEnabled',
            'layoutEnabled',
            'moduleEnabled',
        ];

        $html = '';

        foreach ($fieldNames as $fieldName) {
            $html .= '<td class="tl_folder_list">'.$GLOBALS['TL_LANG']['tl_css_style_selector'][$fieldName][0].'</td>';
        }

        $group = $group ?? '-';

        return $group.'</td>'.$html.'<td class="tl_folder_list">&nbsp;</td>';
    }
}
