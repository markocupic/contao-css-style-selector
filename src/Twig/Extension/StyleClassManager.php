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

namespace Markocupic\ContaoCssStyleSelector\Twig\Extension;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StyleClassManager extends AbstractExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly Connection $connection,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_style_classes', [$this, 'getStyleClasses'], ['needs_context' => true]),
        ];
    }

    /**
     * Append the additional style classes that have been set in the content element.
     */
    public function getStyleClasses(array $_context): string
    {
        $origElementCssClasses = $_context['element_css_classes'] ?? '';

        $request = $this->requestStack->getCurrentRequest();

        if (!$this->scopeMatcher->isFrontendRequest($request)) {
            return $origElementCssClasses;
        }

        $rowContentElement = $_context['data'] ?? [];

        if (empty($dataContentElement)) {
            return $origElementCssClasses;
        }

        $arrStyleIDS = StringUtil::deserialize($rowContentElement['cssStyleSelector'] ?? '', true);

        if (empty($arrStyleIDS)) {
            return $origElementCssClasses;
        }

        $arrElementCssClasses = explode(' ', $origElementCssClasses);

        foreach ($arrStyleIDS as $styleId) {
            $rowCssStyle = $this->connection
                ->fetchAssociative(
                    'SELECT * FROM tl_css_style_selector WHERE id = ?',
                    [
                        $styleId,
                    ],
                )
            ;

            if (false !== $rowCssStyle && !$rowCssStyle['disableInContent']) {
                $arrElementCssClasses = array_merge($arrElementCssClasses, explode(' ', $rowCssStyle['cssClasses']));
            }
        }

        return implode(' ', array_filter(array_unique($arrElementCssClasses)));
    }
}
