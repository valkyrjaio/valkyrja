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

namespace Valkyrja\Tests;

/**
 * Class EnvTest.
 *
 * @author Melech Mizrachi
 */
class EnvTest
{
    public const CONSOLE_QUIET             = true;
    public const CONFIG_CACHE_FILE_PATH    = __DIR__ . '/bootstrap/cache.php';
    public const CONSOLE_FILE_PATH         = __DIR__ . '/bootstrap/commands/default.php';
    public const CONSOLE_CACHE_FILE_PATH   = __DIR__ . '/bootstrap/commands-cache.php';
    public const CONTAINER_CACHE_FILE_PATH = __DIR__ . '/bootstrap/container-cache.php';
    public const CONTAINER_FILE_PATH       = __DIR__ . '/bootstrap/services/default.php';
    public const EVENT_CACHE_FILE_PATH     = __DIR__ . '/bootstrap/events-cache.php';
    public const EVENT_FILE_PATH           = __DIR__ . '/bootstrap/events/default.php';
    public const ROUTING_CACHE_FILE_PATH   = __DIR__ . '/bootstrap/routing-cache.php';
    public const ROUTING_FILE_PATH         = __DIR__ . '/bootstrap/routes/default.php';
}
