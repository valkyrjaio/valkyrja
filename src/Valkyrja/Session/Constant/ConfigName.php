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
    public const DEFAULT_CONFIGURATION = 'defaultConfiguration';
    public const CONFIGURATIONS        = 'configurations';

    public const ADAPTER_CLASS = 'adapterClass';
    public const DRIVER_CLASS  = 'driverClass';

    public const ID   = 'id';
    public const NAME = 'name';

    public const PATH      = 'path';
    public const DOMAIN    = 'domain';
    public const LIFETIME  = 'lifetime';
    public const SECURE    = 'secure';
    public const HTTP_ONLY = 'httpOnly';
    public const SAME_SITE = 'sameSite';
}
