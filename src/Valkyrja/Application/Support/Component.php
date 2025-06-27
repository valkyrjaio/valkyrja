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

namespace Valkyrja\Application\Support;

use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Support\Provider as ContainerProvider;
use Valkyrja\Support\Config;

/**
 * Abstract Class Component.
 *
 * @author Melech Mizrachi
 */
abstract class Component
{
    /**
     * Get the component's config class name.
     *
     * @return class-string<Config>|null
     */
    public static function getConfig(): string|null
    {
        return null;
    }

    /**
     * Get the component's container aliases.
     *
     * @return class-string[]
     */
    public static function getContainerAliases(): array
    {
        return [];
    }

    /**
     * Get the component's container services.
     *
     * @return class-string<Service>[]
     */
    public static function getContainerServices(): array
    {
        return [];
    }

    /**
     * Get the component's container context aliases.
     *
     * @return class-string[]
     */
    public static function getContainerContextAliases(): array
    {
        return [];
    }

    /**
     * Get the component's container context services.
     *
     * @return class-string<Service>[]
     */
    public static function getContainerContextServices(): array
    {
        return [];
    }

    /**
     * Get the component's container service providers.
     *
     * @return class-string<ContainerProvider>[]
     */
    public static function getContainerProviders(): array
    {
        return [];
    }

    /**
     * Get the component's event listeners.
     *
     * @return class-string[]
     */
    public static function getEventListeners(): array
    {
        return [];
    }

    /**
     * Get the component's cli controllers.
     *
     * @return class-string[]
     */
    public static function getCliControllers(): array
    {
        return [];
    }

    /**
     * Get the component's http controllers.
     *
     * @return class-string[]
     */
    public static function getHttpControllers(): array
    {
        return [];
    }

    /**
     * Get the component's name.
     *
     * @return non-empty-string
     */
    abstract public static function getName(): string;
}
