<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Providers;

use Valkyrja\Console\Console;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Kernel;
use Valkyrja\Console\Output\Output;
use Valkyrja\Console\Output\OutputFormatter;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Support\Provider;

/**
 * Class ContainerServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ConsoleServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::CONSOLE,
        CoreComponent::CONSOLE_KERNEL,
        CoreComponent::INPUT,
        CoreComponent::OUTPUT_FORMATTER,
        CoreComponent::OUTPUT,
    ];

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindConsole($app);
        static::bindKernel($app);
        static::bindInput($app);
        static::bindOutputFormatter($app);
        static::bindOutput($app);

        $app->console()->setup();
    }

    /**
     * Bind the console.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindConsole(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::CONSOLE,
            new Console($app)
        );
    }

    /**
     * Bind the kernel.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindKernel(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::CONSOLE_KERNEL,
            new Kernel(
                $app,
                $app->container()->getSingleton(CoreComponent::CONSOLE)
            )
        );
    }

    /**
     * Bind the input.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindInput(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::INPUT,
            new Input(
                $app->container()->getSingleton(CoreComponent::REQUEST),
                $app->container()->getSingleton(CoreComponent::CONSOLE)
            )
        );
    }

    /**
     * Bind the output formatter.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindOutputFormatter(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::OUTPUT_FORMATTER,
            new OutputFormatter()
        );
    }

    /**
     * Bind the output.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindOutput(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::OUTPUT,
            new Output(
                $app->container()->getSingleton(CoreComponent::OUTPUT_FORMATTER)
            )
        );
    }
}
