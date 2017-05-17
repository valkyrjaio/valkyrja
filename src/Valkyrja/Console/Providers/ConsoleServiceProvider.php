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
use Valkyrja\Container\Service;
use Valkyrja\Support\ServiceProvider;

/**
 * Class ContainerServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ConsoleServiceProvider extends ServiceProvider
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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindConsole();
        $this->bindKernel();
        $this->bindInput();
        $this->bindOutputFormatter();
        $this->bindOutput();

        $this->app->console()->setup();
    }

    /**
     * Bind the console.
     *
     * @return void
     */
    protected function bindConsole(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::CONSOLE)
                ->setClass(Console::class)
                ->setDependencies([CoreComponent::APP, CoreComponent::PATH_PARSER, CoreComponent::PATH_GENERATOR])
        );
    }

    /**
     * Bind the kernel.
     *
     * @return void
     */
    protected function bindKernel(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::CONSOLE_KERNEL)
                ->setClass(Kernel::class)
                ->setDependencies([CoreComponent::APP, CoreComponent::CONSOLE])
        );
    }

    /**
     * Bind the input.
     *
     * @return void
     */
    protected function bindInput(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::INPUT)
                ->setClass(Input::class)
                ->setDependencies([CoreComponent::REQUEST, CoreComponent::CONSOLE])
        );
    }

    /**
     * Bind the output formatter.
     *
     * @return void
     */
    protected function bindOutputFormatter(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::OUTPUT_FORMATTER)
                ->setClass(OutputFormatter::class)
                ->setDependencies()
        );
    }

    /**
     * Bind the output.
     *
     * @return void
     */
    protected function bindOutput(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::OUTPUT)
                ->setClass(Output::class)
                ->setDependencies([CoreComponent::OUTPUT_FORMATTER])
        );
    }
}
