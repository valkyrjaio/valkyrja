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

namespace Valkyrja\Session\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Session\Adapters\CacheAdapter;
use Valkyrja\Session\Adapters\CookieAdapter;
use Valkyrja\Session\Adapters\NullAdapter;
use Valkyrja\Session\Adapters\PHPAdapter;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ID       = null;
    public const NAME     = null;
    public const ADAPTER  = CKP::PHP;
    public const ADAPTERS = [
        CKP::CACHE  => CacheAdapter::class,
        CKP::COOKIE => CookieAdapter::class,
        CKP::NULL   => NullAdapter::class,
        CKP::PHP    => PHPAdapter::class,
    ];

    public static array $defaults = [
        CKP::ID       => self::ID,
        CKP::NAME     => self::NAME,
        CKP::ADAPTER  => self::ADAPTER,
        CKP::ADAPTERS => self::ADAPTERS,
    ];
}
