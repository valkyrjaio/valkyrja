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
use Valkyrja\Application;
use Valkyrja\Console\Events\ConsoleKernelHandled;
use Valkyrja\Console\Events\ConsoleKernelTerminate;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Output;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Support\Providers\Provides;

/**
 * Class ConsoleKernel.
 *
 * @author Melech Mizrachi
 */
class NativeKernel implements Kernel
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The console.
     *
     * @var Console
     */
    protected Console $console;

    /**
     * Kernel constructor.
     *
     * @param Application $application The application
     * @param Console     $console     The console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->app     = $application;
        $this->console = $console;
    }

    /**
     * Handle a console input.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @throws HttpException
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

        $this->app->events()->trigger(ConsoleKernelHandled::class, [new ConsoleKernelHandled($input, $exitCode)]);

        return $exitCode;
    }

    /**
     * Terminate the kernel request.
     *
     * @param Input $input    The input
     * @param int   $exitCode The response
     *
     * @return void
     */
    public function terminate(Input $input, int $exitCode): void
    {
        $this->app->events()->trigger(ConsoleKernelTerminate::class, [new ConsoleKernelTerminate($input, $exitCode)]);
    }

    /**
     * Run the kernel.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @throws HttpException
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
            Kernel::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            Kernel::class,
            new static(
                $app,
                $app->console()
            )
        );
    }
}
