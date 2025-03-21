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

namespace Valkyrja\Http\Routing\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const CONTROLLERS     = 'HTTP_ROUTING_CONTROLLERS';
    public const FILE_PATH       = 'HTTP_ROUTING_FILE_PATH';
    public const CACHE_FILE_PATH = 'HTTP_ROUTING_CACHE_FILE_PATH';
    public const USE_CACHE       = 'HTTP_ROUTING_USE_CACHE';
}
