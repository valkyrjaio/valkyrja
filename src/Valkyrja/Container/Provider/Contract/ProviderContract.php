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

namespace Valkyrja\Container\Provider\Contract;

use Valkyrja\Container\Manager\Contract\ContainerContract;

interface ProviderContract
{
    /**
     * Whether this provider is deferred.
     */
    public static function deferred(): bool;

    /**
     * Any custom publishers for services provided by this provider.
     * Any service provided by the `publish` method does not need to be defined here.
     *
     * <code>
     *      [
     *          Provided::class => [self::class, 'publishProvidedClass'],
     *      ]
     *
     * ...
     *      public static function publishProvidedClass(Container $container): void
     *      {
     *          $container->setSingleton(Provided::class, new Provided());
     *      }
     * </code>
     *
     * @return array<class-string, callable>
     */
    public static function publishers(): array;

    /**
     * The services provided by this provider.
     *
     * @return class-string[]
     */
    public static function provides(): array;

    /**
     * Publish the provider.
     */
    public static function publish(ContainerContract $container): void;
}
