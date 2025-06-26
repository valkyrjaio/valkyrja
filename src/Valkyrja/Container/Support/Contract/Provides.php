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

namespace Valkyrja\Container\Support\Contract;

/**
 * Interface Provides.
 *
 * @author Melech Mizrachi
 */
interface Provides
{
    /**
     * Whether this provider is deferred.
     *
     * @return bool
     */
    public static function deferred(): bool;

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
    public static function publishers(): array;

    /**
     * The items provided by this provider.
     *
     * @return class-string[]
     */
    public static function provides(): array;

    /**
     * Publish the provider.
     *
     * @param object $providerAware The providers aware class
     *
     * @return void
     */
    public static function publish(object $providerAware): void;
}
