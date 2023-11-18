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
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Form;
use Contao\Widget;
use Doctrine\DBAL\Connection;
use Markocupic\ContaoCssStyleSelector\ContentType\ContentTypeFormField;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('loadFormField')]
final class LoadFormFieldListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly Connection $connection,
        private readonly ContaoFramework $framework,
    ) {
    }

    public function __invoke(Widget $widget, string $formId, array $formData, Form $form): Widget
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$this->scopeMatcher->isFrontendRequest($request)) {
            return $widget;
        }

        $arrData = $this->connection->fetchAssociative('SELECT * FROM tl_form_field WHERE id = ?', [$widget->id]);

        if (false === $arrData) {
            return $widget;
        }

        $contentTypeHandler = new ContentTypeFormField($this->framework);

        $arrStyleIDS = $contentTypeHandler->getCssStyle($arrData, $request);

        if (empty($arrStyleIDS)) {
            return $widget;
        }

        $arrClasses = explode(' ', $arrData['class'] ?? '');

        foreach ($arrStyleIDS as $styleId) {
            $arrStyle = $this->connection
                ->fetchAssociative(
                    'SELECT * FROM tl_css_style_selector WHERE id = ?',
                    [
                        $styleId,
                    ],
                )
                ;

            if (false !== $arrStyle && !$contentTypeHandler->isDisabled($arrStyle, $request) && !empty($arrStyle['cssClasses'])) {
                $arrClasses = array_merge($arrClasses, explode(' ', $arrStyle['cssClasses']));
            }
        }

        $widget->class = implode(' ', array_unique(array_filter($arrClasses)));

        return $widget;
    }
}
