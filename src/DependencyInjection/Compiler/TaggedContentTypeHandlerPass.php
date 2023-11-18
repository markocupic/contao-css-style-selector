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

namespace Markocupic\ContaoCssStyleSelector\DependencyInjection\Compiler;

use Markocupic\ContaoCssStyleSelector\Util\ContentTypeUtil;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaggedContentTypeHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ContentTypeUtil::class)) {
            return;
        }

        $definition = $container->findDefinition(ContentTypeUtil::class);
        $services = [];

        $serviceIds = $container->findTaggedServiceIds(
            'markocupic_css_style_selector.content_type_handler',
            true,
        );

        foreach ($serviceIds as $serviceId => $attributes) {
            $priority = $attributes[0]['priority'] ?? 0;
            $class = $container->getDefinition($serviceId)->getClass();
            $services[$priority][$class] = new Reference($serviceId);
        }

        foreach (array_keys($services) as $priority) {
            ksort($services[$priority], SORT_NATURAL); // Order by class name ascending
        }

        if ($services) {
            krsort($services); // Order by priority descending
            $services = array_merge(...$services);

            foreach (array_keys($services) as $serviceId) {
                $definition->addMethodCall('addContentTypeHandler', [$serviceId]);
            }
        }
    }
}
