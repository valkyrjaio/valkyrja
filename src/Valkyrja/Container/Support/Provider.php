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

namespace Valkyrja\Container\Support;

use Valkyrja\Container\Contract\Container;

/**
 * Abstract Class Provider.
 *
 * @author Melech Mizrachi
 */
abstract class Provider
{
    /**
     * Whether this provider is deferred.
     *
     * @return bool
     */
    public static function deferred(): bool
    {
        return true;
    }

    /**
     * Any custom publishers for items provided by this provider.
     *
     * <code>
     *      [
     *          Provided::class => [self::class, 'publish'],
     *          Provided::class => [self::class, 'publishProvidedClass'],
     *      ]
     *
     * ...
     *      public static function publishProvidedClass(Container $container): void
     * </code>
     *
     * @return array<class-string, callable>
     */
    public static function publishers(): array
    {
        return [];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * The items provided by this provider.
     *
     * @return class-string[]
     */
    abstract public static function provides(): array;
}
