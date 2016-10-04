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
     * ServiceProvider constructor.
     */
    public function __construct()
    {
        $this->publish();
    }

    /**
     * Publish the service provider.
     */
    abstract public function publish();
}
