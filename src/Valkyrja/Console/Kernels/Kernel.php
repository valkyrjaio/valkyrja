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

namespace Valkyrja\Console\Kernels;

use Throwable;
use Valkyrja\Console\Console;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Events\ConsoleKernelHandled;
use Valkyrja\Console\Events\ConsoleKernelTerminate;
use Valkyrja\Console\Input;
use Valkyrja\Console\Kernel as Contract;
use Valkyrja\Console\Output;
use Valkyrja\Container\Container;
use Valkyrja\Event\Events;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Log\Logger;

use function Valkyrja\dd;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class Kernel implements Contract
{
    /**
     * The console.
     *
     * @var Console
     */
    protected Console $console;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The events manager.
     *
     * @var Events
     */
    protected Events $events;

    /**
     * Kernel constructor.
     *
     * @param Console   $console   The console
     * @param Container $container The container
     * @param Events    $events    The events manager
     */
    public function __construct(Console $console, Container $container, Events $events)
    {
        $this->console   = $console;
        $this->container = $container;
        $this->events    = $events;
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
            $this->logException($exception);

            $exitCode = ExitCode::FAILURE;
        }

        $this->events->trigger(ConsoleKernelHandled::class, [new ConsoleKernelHandled($input, $exitCode)]);

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
        $this->events->trigger(ConsoleKernelTerminate::class, [new ConsoleKernelTerminate($input, $exitCode)]);
    }

    /**
     * Run the kernel.
     *
     * @param Input|null  $input  The input
     * @param Output|null $output The output
     *
     * @return int
     */
    public function run(Input $input = null, Output $output = null): int
    {
        $this->container->setSingleton(
            Input::class,
            $input ?? $input = $this->container->getSingleton(Input::class)
        );

        $this->container->setSingleton(
            Output::class,
            $output ?? $output = $this->container->getSingleton(Output::class)
        );

        // Handle the request and get the response
        $exitCode = $this->handle($input, $output);

        // Terminate the application
        $this->terminate($input, $exitCode);

        return $exitCode;
    }

    /**
     * Log an error.
     *
     * @param Throwable $exception
     *
     * @return void
     */
    protected function logException(Throwable $exception): void
    {
        /** @var Logger $logger */
        $logger = $this->container->getSingleton(Logger::class);

        $logger->error((string) $exception);
    }
}
