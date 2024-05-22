<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Provider;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Routing\Collection\Contract\Collection;
use Valkyrja\Routing\Matcher\Contract\Matcher;
use Valkyrja\Routing\Matcher\EntityCapableMatcher;

/**
 * Class EntityMatcherServiceProvider.
 *
 * @author Melech Mizrachi
 */
class EntityMatcherServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Matcher::class => [self::class, 'publishMatcher'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Matcher::class,
        ];
    }

    /**
     * Publish the matcher service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMatcher(Container $container): void
    {
        $container->setSingleton(
            Matcher::class,
            new EntityCapableMatcher(
                $container,
                $container->getSingleton(Collection::class)
            )
        );
    }
}
