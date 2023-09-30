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

namespace Markocupic\ContaoCssStyleSelector\Models;

use Contao\Config;
use Contao\Database;
use Contao\Model;

class CssStyleSelectorModel extends Model
{
    public const TYPE_ARTICLE = 'article';
    public const TYPE_CALENDAR_EVENTS = 'calendarEvent';
    public const TYPE_CONTENT = 'content';
    public const TYPE_FORM = 'form';
    public const TYPE_FORM_FIELD = 'formField';
    public const TYPE_LAYOUT = 'layout';
    public const TYPE_NEWS = 'news';
    public const TYPE_MODEL = 'module';
    public const TYPE_PAGE = 'page';

    protected static $strTable = 'tl_css_style_selector';

    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_ARTICLE,
            self::TYPE_CALENDAR_EVENTS,
            self::TYPE_CONTENT,
            self::TYPE_FORM,
            self::TYPE_FORM_FIELD,
            self::TYPE_LAYOUT,
            self::TYPE_NEWS,
            self::TYPE_MODEL,
            self::TYPE_PAGE,
        ];
    }

    public static function getTypeByTable(string $table): string
    {
        return match ($table) {
            'tl_article' => self::TYPE_ARTICLE,
            'tl_calendar_events' => self::TYPE_CALENDAR_EVENTS,
            'tl_content' => self::TYPE_CONTENT,
            'tl_form' => self::TYPE_FORM,
            'tl_form_field' => self::TYPE_FORM_FIELD,
            'tl_layout' => self::TYPE_LAYOUT,
            'tl_news' => self::TYPE_NEWS,
            'tl_model' => self::TYPE_MODEL,
            'tl_page' => self::TYPE_PAGE,
            default => null,
        };
    }

    public static function findStyleDesignationByNotDisabledType(string $type): array
    {
        if (!\in_array($type, self::getAvailableTypes(), true)) {
            return [];
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare(
                "SELECT id, styleDesignation, styleGroup, cssClasses FROM $t WHERE disableIn".ucfirst(
                    $type
                ).'=? ORDER BY styleGroup, styleDesignation'
            )
            ->execute(0)
        ;

        $styles = [];

        foreach ($objCssStyleSelector->fetchAllAssoc() as $item) {
            $value = $item['styleDesignation'];

            if ('' !== $item['cssClasses'] && Config::get('cssStyleSelectorAddClassesToListItem')) {
                $value .= ' ('.$item['cssClasses'].')';
            }

            if ($item['styleGroup']) {
                $styles[$item['styleGroup']][$item['id']] = $value;
            } else {
                $styles[$item['id']] = $value;
            }
        }

        foreach ($styles as $style) {
            if (\is_array($style)) {
                natsort($style);
            }
        }

        natsort($styles);

        return $styles;
    }
}
