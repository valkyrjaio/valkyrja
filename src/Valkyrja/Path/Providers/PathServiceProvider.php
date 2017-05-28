<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Path\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Support\Provider;

/**
 * Class PathServiceProvider.
 *
 * @author Melech Mizrachi
 */
class PathServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::PATH_GENERATOR,
        CoreComponent::PATH_PARSER,
    ];

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return static::$provides;
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindPathGenerator($app);
        static::bindPathParser($app);
    }

    /**
     * Bind the path generator.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindPathGenerator(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::PATH_GENERATOR,
            new PathGenerator()
        );
    }

    /**
     * Bind the path parser.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindPathParser(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::PATH_PARSER,
            new PathParser()
        );
    }
}
