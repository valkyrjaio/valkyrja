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

namespace Valkyrja\Container\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const ALIASES          = 'CONTAINER_ALIASES';
    public const SERVICES         = 'CONTAINER_SERVICES';
    public const CONTEXT_SERVICES = 'CONTAINER_CONTEXT_SERVICES';
    public const PROVIDERS        = 'CONTAINER_PROVIDERS';
    public const DEV_PROVIDERS    = 'CONTAINER_DEV_PROVIDERS';
    public const USE_ATTRIBUTES   = 'CONTAINER_USE_ATTRIBUTES';
    public const FILE_PATH        = 'CONTAINER_FILE_PATH';
    public const CACHE_FILE_PATH  = 'CONTAINER_CACHE_FILE_PATH';
    public const USE_CACHE        = 'CONTAINER_USE_CACHE';
}
