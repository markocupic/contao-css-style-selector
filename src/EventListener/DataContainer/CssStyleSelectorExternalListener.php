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
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Markocupic\ContaoCssStyleSelector\Models\CssStyleSelectorModel;

class CssStyleSelectorExternalListener
{
    #[AsCallback(table: 'tl_article', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_calendar_events', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_content', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_form', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_form_field', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_layout', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_module', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_news', target: 'fields.cssStyleSelector.options')]
    #[AsCallback(table: 'tl_page', target: 'fields.cssStyleSelector.options')]
    public function getCssStyleSelectorOptions(DataContainer $dc): array
    {
        $type = CssStyleSelectorModel::getTypeByTable($dc->table);

        if (!$type) {
            return [];
        }

        return CssStyleSelectorModel::findStyleDesignationByNotDisabledType($type);
    }

    /**
     * onload_callback for the tl_content DCA to inject cssStyleSelector for any regular custom content element.
     */
    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    public function onLoadContent(DataContainer|null $dc): void
    {
        if (!($dc instanceof DataContainer)) {
            return;
        }

        // Get the type
        $type = null;

        if (Input::post('FORM_SUBMIT') === $dc->table) {
            $type = Input::post('type');
        } else {
            if ($dc->activeRecord) {
                $type = $dc->activeRecord->type;
            } else {
                $table = $dc->table;
                $id = $dc->id;

                if (Input::get('target')) {
                    $table = explode('.', Input::get('target'), 2)[0];
                    $id = (int) explode('.', Input::get('target'), 3)[2];
                }

                if ($table && $id) {
                    $record = Database::getInstance()->prepare("SELECT * FROM $table WHERE id=?")->execute($id);

                    if ($record->next()) {
                        $type = $record->type;
                    }
                }
            }
        }

        // The palette might not exist
        if (\array_key_exists($type, $GLOBALS['TL_DCA'][$dc->table]['palettes'])) {
            // Get the palette
            $palette = &$GLOBALS['TL_DCA'][$dc->table]['palettes'][$type];

            // Check if cssID is in the palette and cssStyleSelector is not
            if (str_contains($palette, 'cssID') && !str_contains($palette, 'cssStyleSelector')) {
                // Add the css style selector
                $palette = str_replace(',cssID', ',cssStyleSelector,cssID', $palette);
            }
        }
    }
}
