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

namespace Markocupic\ContaoCssStyleSelector\ContentType;

use Symfony\Component\HttpFoundation\Request;

interface ContentTypeInterface
{
    public function getTemplateIdentifierSlug(Request $request): string|null;

    public function getCssStyle(array $arrData, Request $request): array;

    public function isDisabled(array $arrStyle, Request $request): bool;
}
