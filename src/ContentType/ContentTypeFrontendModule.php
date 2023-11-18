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

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;

final class ContentTypeFrontendModule implements ContentTypeInterface
{
    public const TEMPLATE_STARTS_WITH = 'mod_';

    public function __construct(
        private readonly ContaoFramework $framework,
    ) {
    }

    public function getTemplateIdentifierSlug(Request $request): string
    {
        return self::TEMPLATE_STARTS_WITH;
    }

    public function getCssStyle(array $arrData, Request $request): array
    {
        $stringUtil = $this->framework->getAdapter(StringUtil::class);

        return $stringUtil->deserialize($arrData['cssStyleSelector'] ?? '', true);
    }

    public function isDisabled(array $arrStyle, Request $request): bool
    {
        return (bool) $arrStyle['disableInContent'];
    }
}
