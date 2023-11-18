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
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;

final class ContentTypePage implements ContentTypeInterface
{
    public const TEMPLATE_STARTS_WITH = 'fe_page';

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
        $pageModel = $this->getPageModel($request);

        if (null === $pageModel) {
            return [];
        }

        $arrPage = $pageModel->row();

        $stringUtil = $this->framework->getAdapter(StringUtil::class);

        return $stringUtil->deserialize($arrPage['cssStyleSelector'] ?? '', true);
    }

    public function isDisabled(array $arrStyle, Request $request): bool
    {
        return (bool) $arrStyle['disableInPage'];
    }

    private function getPageModel(Request $request): PageModel|null
    {
        if (!$request->attributes->has('pageModel')) {
            if (isset($GLOBALS['objPage']) && $GLOBALS['objPage'] instanceof PageModel) {
                return $GLOBALS['objPage'];
            }

            return null;
        }

        $pageModel = $request->attributes->get('pageModel');

        if ($pageModel instanceof PageModel) {
            return $pageModel;
        }

        if (
            isset($GLOBALS['objPage'])
            && $GLOBALS['objPage'] instanceof PageModel
            && (int) $GLOBALS['objPage']->id === (int) $pageModel
        ) {
            return $GLOBALS['objPage'];
        }

        $this->framework->initialize();

        return $this->framework->getAdapter(PageModel::class)->findByPk((int) $pageModel);
    }
}
