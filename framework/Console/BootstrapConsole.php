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

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Console;

/**
 * Class BootstrapConsole
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class BootstrapConsole
{
    protected $app;

    protected $console;

    /**
     * BootstrapConsole constructor.
     *
     * @param \Valkyrja\Contracts\Application     $application The application
     * @param \Valkyrja\Contracts\Console\Console $console     The console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->app = $application;
        $this->console = $console;
    }

    protected function bootstrap(): void
    {
    }
}
