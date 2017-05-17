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
use Valkyrja\Container\Service;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Support\ServiceProvider;

/**
 * Class PathServiceProvider.
 *
 * @author Melech Mizrachi
 */
class PathServiceProvider extends ServiceProvider
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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindPathGenerator();
        $this->bindPathParser();
    }

    /**
     * Bind the path generator.
     *
     * @return void
     */
    protected function bindPathGenerator(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::PATH_GENERATOR)
                ->setClass(PathGenerator::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bind the path parser.
     *
     * @return void
     */
    protected function bindPathParser(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::PATH_PARSER)
                ->setClass(PathParser::class)
                ->setSingleton(true)
        );
    }
}
