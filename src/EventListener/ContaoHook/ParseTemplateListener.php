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
use Contao\StringUtil;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('parseTemplate')]
final class ParseTemplateListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly Connection $connection,
    ) {
    }

    public function __invoke(Template $template): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$this->scopeMatcher->isFrontendRequest($request)) {
            return;
        }

        $arrDataTemplate = $template->getData();

        if (str_contains($template->getName(), 'ce_') || str_contains($template->getName(), 'mod_')) {
            $arrStyleIDS = StringUtil::deserialize($arrDataTemplate['cssStyleSelector'] ?? '', true);

            if (empty($arrStyleIDS)) {
                return;
            }

            $arrClasses = explode(' ', $arrDataTemplate['class'] ?? '');

            foreach ($arrStyleIDS as $styleId) {
                $arrStyle = $this->connection
                    ->fetchAssociative(
                        'SELECT * FROM tl_css_style_selector WHERE id = ?',
                        [
                            $styleId,
                        ],
                    )
                ;

                if (false !== $arrStyle && !$arrStyle['disableInContent'] && !empty($arrStyle['cssClasses'])) {
                    $arrClasses = array_merge($arrClasses, explode(' ', $arrStyle['cssClasses']));
                }
            }

            $template->class = implode(' ', array_unique(array_filter($arrClasses)));
        }
    }
}
