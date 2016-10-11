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
use Valkyrja\Application;

/**
 * Class ServiceProvider
 *
 * @package Valkyrja\Support
 *
 * @author Melech Mizrachi
 */
abstract class ServiceProvider
{
    /**
     * The application.
     *
     * @var Application
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
     */
    abstract public function publish();
}
