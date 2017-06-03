<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Throwable;
use Valkyrja\Container\CoreComponent;
use Valkyrja\Application;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Output;
use Valkyrja\Support\Providers\Provides;

/**
 * Class ConsoleKernel.
 *
 * @author Melech Mizrachi
 */
class KernelImpl implements Kernel
{
    use Provides;

    /**
     * The application.
     *
     * @var \Valkyrja\Application
     */
    protected $app;

    /**
     * The console.
     *
     * @var \Valkyrja\Console\Console
     */
    protected $console;

    /**
     * Kernel constructor.
     *
     * @param \Valkyrja\Application     $application The application
     * @param \Valkyrja\Console\Console $console     The console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->app     = $application;
        $this->console = $console;
    }

    /**
     * Handle a console input.
     *
     * @param \Valkyrja\Console\Input\Input   $input  The input
     * @param \Valkyrja\Console\Output\Output $output The output
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return int
     */
    public function handle(Input $input, Output $output): int
    {
        $exitCode = 1;

        try {
            $exitCode = $this->console->dispatch($input, $output);
        } catch (Throwable $exception) {
            // Show the exception
            // TODO: Implement
            dd($exception);
        }

        $this->app->events()->trigger('Console.Kernel.handled', [$input, $exitCode]);

        return $exitCode;
    }

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Console\Input\Input $input    The input
     * @param int                           $exitCode The response
     *
     * @return void
     */
    public function terminate(Input $input, int $exitCode): void
    {
        $this->app->events()->trigger('Console.Kernel.terminate', [$input, $exitCode]);
    }

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Console\Input\Input   $input  The input
     * @param \Valkyrja\Console\Output\Output $output The output
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    public function run(Input $input = null, Output $output = null): void
    {
        // If no input was passed get the bootstrapped definition
        if (null === $input) {
            $input = $this->app->container()->get(Input::class);
        } else {
            $this->app->container()->singleton(Input::class, $input);
        }

        // If no output was passed get the bootstrapped definition
        if (null === $output) {
            $output = $this->app->container()->get(Output::class);
        } else {
            $this->app->container()->singleton(Output::class, $output);
        }

        // Handle the request and get the response
        $exitCode = $this->handle($input, $output);

        // Terminate the application
        $this->terminate($input, $exitCode);

        exit($exitCode);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::CONSOLE_KERNEL,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::CONSOLE_KERNEL,
            new static(
                $app,
                $app->container()->getSingleton(CoreComponent::CONSOLE)
            )
        );
    }
}
