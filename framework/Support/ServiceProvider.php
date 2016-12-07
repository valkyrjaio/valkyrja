<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

use Valkyrja\Contracts\Application;

/**
 * Class ServiceProvider
 *
 * @package Valkyrja\Support
 *
 * @author  Melech Mizrachi
 */
abstract class ServiceProvider
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * ServiceProvider constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->publish();
    }

    /**
     * Publish the service provider.
     *
     * @return void
     */
    abstract public function publish(); // : void;
}
