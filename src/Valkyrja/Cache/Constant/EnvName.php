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

namespace Valkyrja\Cache\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'CACHE_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'CACHE_CONFIGURATIONS';

    public const string REDIS_ADAPTER_CLASS = 'CACHE_REDIS_ADAPTER_CLASS';
    public const string REDIS_DRIVER_CLASS  = 'CACHE_REDIS_DRIVER_CLASS';
    public const string REDIS_HOST          = 'CACHE_REDIS_HOST';
    public const string REDIS_PORT          = 'CACHE_REDIS_PORT';
    public const string REDIS_PREFIX        = 'CACHE_REDIS_PREFIX';

    public const string LOG_ADAPTER_CLASS = 'CACHE_LOG_ADAPTER_CLASS';
    public const string LOG_DRIVER_CLASS  = 'CACHE_LOG_DRIVER_CLASS';
    public const string LOG_PREFIX        = 'CACHE_LOG_PREFIX';
    public const string LOG_LOGGER        = 'CACHE_LOG_LOGGER';

    public const string NULL_ADAPTER_CLASS = 'CACHE_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'CACHE_NULL_DRIVER_CLASS';
}
