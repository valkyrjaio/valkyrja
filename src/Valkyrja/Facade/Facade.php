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

namespace Valkyrja\Facade;

use RuntimeException;

/**
 * Interface Facade.
 *
 * @author Melech Mizrachi
 */
interface Facade
{
    /**
     * Get the instance.
     *
     * @return object
     */
    public static function getInstance(): object;

    /**
     * Set the instance.
     *
     * @param string|object  $instance
     *
     * @return void
     */
    public static function setInstance($instance): void;

    /**
     * Handle dynamic, static calls to the instance.
     *
     * @param string $method The method to call
     * @param array  $args   The argument
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args = []);
}
