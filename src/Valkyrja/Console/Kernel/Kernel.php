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

namespace Valkyrja\Console\Kernel;

use Throwable;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Event\ConsoleKernelHandled;
use Valkyrja\Console\Event\ConsoleKernelTerminate;
use Valkyrja\Console\Input\Contract\Input;
use Valkyrja\Console\Kernel\Contract\Kernel as Contract;
use Valkyrja\Console\Output\Contract\Output;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Event\Contract\Dispatcher as Events;
use Valkyrja\Log\Contract\Logger;

use function var_dump;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class Kernel implements Contract
{
    /**
     * Kernel constructor.
     *
     * @param Console   $console   The console
     * @param Container $container The container
     * @param Events    $events    The events manager
     */
    public function __construct(
        protected Console $console,
        protected Container $container,
        protected Events $events
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handle(Input $input, Output $output): int
    {
        try {
            $exitCode = $this->console->dispatch($input, $output);
        } catch (Throwable $exception) {
            // Log the error
            $this->logException($exception);

            // Show the exception
            // TODO: Implement
            var_dump($exception);

            $exitCode = ExitCode::FAILURE;
        }

        $this->events->dispatchByIdIfHasListeners(
            ConsoleKernelHandled::class,
            [$input, $exitCode]
        );

        return $exitCode;
    }

    /**
     * @inheritDoc
     */
    public function terminate(Input $input, int $exitCode): void
    {
        $this->events->dispatchByIdIfHasListeners(
            ConsoleKernelTerminate::class,
            [$input, $exitCode]
        );
    }

    /**
     * @inheritDoc
     */
    public function run(Input|null $input = null, Output|null $output = null): int
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

        $logger->exception($exception, 'Kernel Error');
    }
}
