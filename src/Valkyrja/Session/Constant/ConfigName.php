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
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string DEFAULT_CONFIGURATION = 'defaultConfiguration';
    public const string CONFIGURATIONS        = 'configurations';

    public const string ADAPTER_CLASS = 'adapterClass';
    public const string DRIVER_CLASS  = 'driverClass';

    public const string ID        = 'id';
    public const string NAME      = 'name';
    public const string CACHE     = 'cache';
    public const string LOGGER    = 'logger';
    public const string PATH      = 'path';
    public const string DOMAIN    = 'domain';
    public const string LIFETIME  = 'lifetime';
    public const string SECURE    = 'secure';
    public const string HTTP_ONLY = 'httpOnly';
    public const string SAME_SITE = 'sameSite';
}
