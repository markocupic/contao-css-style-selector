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

use Contao\DC_Table;
use Contao\DataContainer;

/**
 * Table tl_css_style_selector_group
 */
$GLOBALS['TL_DCA']['tl_css_style_selector_group'] = [
    // Config
    'config'   => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    // List
    'list'     => [
        'sorting'           => [
            'mode'            => DataContainer::MODE_SORTED,
            'flag'            => DataContainer::SORT_ASC,
            'fields'          => ['name'],
            'panelLayout'     => 'search,limit',
            'disableGrouping' => true,
        ],
        'label'             => [
            'fields'      => [
                'name',
            ],
            'showColumns' => true,
        ],
        'global_operations' => [
            'css_style_selector_styles' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['css_style_selector_styles'],
                'href'       => 'table=tl_css_style_selector',
                'class'      => 'header_edit_styles',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
            'all'                       => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector_group']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector_group']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_css_style_selector_group']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector_group']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],
    // Palettes
    'palettes' => [
        'default' => '{group_legend},name',
    ],
    // Fields
    'fields'   => [
        'id'     => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'name'   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_css_style_selector_group']['name'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
    ],
];
