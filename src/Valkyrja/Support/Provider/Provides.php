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

namespace Valkyrja\Support\Provider;

/**
 * Trait Provides.
 *
 * @author Melech Mizrachi
 */
trait Provides
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
     * The items provided by this provider.
     *
     * <code>
     *      [
     *          Provided::class => [self::class, 'publish'],
     *          Provided::class => [self::class, 'publishProvidedClass'],
     *      ]
     *
     * ...
     *      public static function publishProvidedClass(Application $app): void
     * </code>
     *
     * @return array<class-string, callable>
     */
    public static function publishers(): array
    {
        return [];
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    abstract public static function provides(): array;

    /**
     * Publish the provider.
     *
     * @param object $providerAware The providers aware class
     *
     * @return void
     */
    abstract public static function publish(object $providerAware): void;
}
