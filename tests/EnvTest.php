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

namespace Valkyrja\Tests;

/**
 * Class EnvTest.
 *
 * @author Melech Mizrachi
 */
class EnvTest
{
    /**
     * Console env variables.
     */
    public const CONSOLE_QUIET             = true;
    public const CONFIG_CACHE_FILE_PATH    = './tests/bootstrap/cache.php';
    public const CONSOLE_CACHE_FILE_PATH   = './tests/bootstrap/commands-cache.php';
    public const CONTAINER_CACHE_FILE_PATH = './tests/bootstrap/container-cache.php';
    public const EVENTS_CACHE_FILE_PATH    = './tests/bootstrap/events-cache.php';
    public const ROUTING_CACHE_FILE_PATH   = './tests/bootstrap/routing-cache.php';
}
