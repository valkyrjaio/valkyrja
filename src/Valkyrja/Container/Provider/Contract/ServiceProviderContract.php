<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Provider\Contract;

use Valkyrja\Container\Manager\Contract\ContainerContract;

interface ServiceProviderContract
{
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
     *      public static function publishProvidedClass(ContainerContract $container): void
     *      {
     *          $container->setSingleton(Provided::class, new Provided());
     *      }
     * </code>
     *
     * @return array<class-string, callable(ContainerContract):void>
     */
    public function publishers(): array;
}
