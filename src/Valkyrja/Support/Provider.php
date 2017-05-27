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
 * Abstract Class Provider.
 *
 * @author Melech Mizrachi
 */
abstract class Provider
{
    /**
     * Whether the provider is deferred.
     *
     * @var bool
     */
    public static $deferred = true;

    /**
     * What classes are provided.
     *
     * @var array
     */
    public static $provides = [];

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    abstract public static function publish(Application $app): void;
}
