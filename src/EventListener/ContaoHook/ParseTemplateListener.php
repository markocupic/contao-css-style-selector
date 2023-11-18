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

namespace Markocupic\ContaoCssStyleSelector\EventListener\ContaoHook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Markocupic\ContaoCssStyleSelector\ContentType\ContentTypeInterface;
use Markocupic\ContaoCssStyleSelector\Util\ContentTypeUtil;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('parseTemplate')]
final class ParseTemplateListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly Connection $connection,
        private readonly ContentTypeUtil $contentTypeUtil,
    ) {
    }

    public function __invoke(Template $template): void
    {

        $request = $this->requestStack->getCurrentRequest();

        if (!$this->scopeMatcher->isFrontendRequest($request)) {
            return;
        }

        $arrData = $template->getData();

        /** @var array<ContentTypeInterface> $arrContentTypeHandlerInstances */
        $arrContentTypeHandlerInstances = $this->contentTypeUtil->getContentTypeHandlerInstances($template->getName());

        $arrClasses = explode(' ', (string) $template->class);

        foreach ($arrContentTypeHandlerInstances as $contentTypeHandlerInstance) {
            $arrStyleIDS = $contentTypeHandlerInstance->getCssStyle($arrData, $request);

            if (empty($arrStyleIDS)) {
                continue;
            }

            $arrClasses = array_merge($arrClasses, explode(' ', $arrData['class'] ?? ''));

            foreach ($arrStyleIDS as $styleId) {
                $arrStyle = $this->connection
                    ->fetchAssociative(
                        'SELECT * FROM tl_css_style_selector WHERE id = ?',
                        [
                            $styleId,
                        ],
                    )
                ;

                if (false !== $arrStyle && !$contentTypeHandlerInstance->isDisabled($arrStyle, $request) && !empty($arrStyle['cssClasses'])) {
                    $arrClasses = array_merge($arrClasses, explode(' ', $arrStyle['cssClasses']));
                }
            }
        }

        $template->class = implode(' ', array_unique(array_filter($arrClasses)));
    }
}
