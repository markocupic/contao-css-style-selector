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

namespace Markocupic\ContaoCssStyleSelector\Util;

use Contao\CoreBundle\Framework\ContaoFramework;
use Markocupic\ContaoCssStyleSelector\ContentType\ContentTypeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ContentTypeUtil
{
    private static array $contentTypeHandlers = [];

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * Used by compiler pass Markocupic\ContaoCssStyleSelector\DependencyInjection\Compiler\TaggedContentTypeHandlerPass.
     */
    public function addContentTypeHandler(string $serviceId): void
    {
        self::$contentTypeHandlers[] = $serviceId;
    }

    public function getContentTypeHandlerInstances(string $templateName): array
    {
        $arrInstances = [];

        $request = $this->requestStack->getCurrentRequest();

        foreach (self::$contentTypeHandlers as $serviceId) {
            /** @var ContentTypeInterface $contentTypeHandler */
            $contentTypeHandler = new $serviceId($this->framework);

            if (null !== $contentTypeHandler->getTemplateIdentifierSlug($request) && str_starts_with($templateName, $contentTypeHandler->getTemplateIdentifierSlug($request))) {
                $arrInstances[] = $contentTypeHandler;
            }
        }

        return $arrInstances;
    }
}
