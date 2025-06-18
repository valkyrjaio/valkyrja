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

namespace Valkyrja\Session\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'SESSION_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'SESSION_CONFIGURATIONS';

    public const string PHP_ADAPTER_CLASS = 'SESSION_PHP_ADAPTER_CLASS';
    public const string PHP_DRIVER_CLASS  = 'SESSION_PHP_DRIVER_CLASS';
    public const string PHP_ID            = 'SESSION_PHP_ID';
    public const string PHP_NAME          = 'SESSION_PHP_NAME';

    public const string CACHE_ADAPTER_CLASS = 'SESSION_CACHE_ADAPTER_CLASS';
    public const string CACHE_DRIVER_CLASS  = 'SESSION_CACHE_DRIVER_CLASS';
    public const string CACHE_CACHE         = 'SESSION_CACHE_CACHE';

    public const string COOKIE_ADAPTER_CLASS = 'SESSION_COOKIE_ADAPTER_CLASS';
    public const string COOKIE_DRIVER_CLASS  = 'SESSION_COOKIE_DRIVER_CLASS';

    public const string LOG_ADAPTER_CLASS = 'SESSION_LOG_ADAPTER_CLASS';
    public const string LOG_DRIVER_CLASS  = 'SESSION_LOG_DRIVER_CLASS';
    public const string LOG_LOGGER        = 'SESSION_LOG_LOGGER';

    public const string NULL_ADAPTER_CLASS = 'SESSION_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'SESSION_NULL_DRIVER_CLASS';
}
