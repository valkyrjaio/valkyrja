<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Kernels;

use Throwable;
use Valkyrja\Application\Application;
use Valkyrja\Console\Console;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Events\ConsoleKernelHandled;
use Valkyrja\Console\Events\ConsoleKernelTerminate;
use Valkyrja\Console\Input;
use Valkyrja\Console\Kernel as KernelContract;
use Valkyrja\Console\Output;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class Kernel implements KernelContract
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
        try {
            $exitCode = $this->console->dispatch($input, $output);
        } catch (Throwable $exception) {
            // Show the exception
            // TODO: Implement
            dd($exception);

            // Log the error
            $this->app->logger()->error((string) $exception);

            $exitCode = ExitCode::FAILURE;
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
     * @return int
     */
    public function run(Input $input = null, Output $output = null): int
    {
        $container = $this->app->container();

        $container->singleton(
            Input::class,
            $input ?? $input = $container->get(Input::class)
        );

        $container->singleton(
            Output::class,
            $output ?? $output = $container->get(Output::class)
        );

        // Handle the request and get the response
        $exitCode = $this->handle($input, $output);

        // Terminate the application
        $this->terminate($input, $exitCode);

        return $exitCode;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::CONSOLE_KERNEL,
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
            Contract::CONSOLE_KERNEL,
            new static(
                $app,
                $app->console()
            )
        );
    }
}
