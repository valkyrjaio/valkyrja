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

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Console;
use Valkyrja\Contracts\Console\Kernel as KernelContract;

/**
 * Class ConsoleKernel
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Kernel implements KernelContract
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The console.
     *
     * @var \Valkyrja\Contracts\Console\Console
     */
    protected $console;

    /**
     * Kernel constructor.
     *
     * @param \Valkyrja\Contracts\Application     $application The application
     * @param \Valkyrja\Contracts\Console\Console $console     The console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->app = $application;
        $this->console = $console;
    }

    /**
     * Handle a console input.
     *
     * @return mixed
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     */
    public function handle()
    {
        try {
        }
        catch (Throwable $exception) {
        }

        $this->app->events()->trigger('Console.Kernel.handled');

        return null;
    }

    /**
     * Terminate the kernel request.
     *
     * @return void
     */
    public function terminate(): void
    {
        $this->app->events()->trigger('Console.Kernel.terminate');
    }

    /**
     * Run the kernel.
     *
     * @return void
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     */
    public function run(): void
    {
        // Handle the request and send the response
        $response = $this->handle()->send();

        // Terminate the application
        $this->terminate();
    }
}
