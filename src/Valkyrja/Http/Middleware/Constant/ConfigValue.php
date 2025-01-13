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

namespace Valkyrja\Http\Middleware\Constant;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const BEFORE            = [];
    public const DISPATCHED        = [];
    public const EXCEPTION         = [];
    public const ROUTE_MATCHED     = [];
    public const ROUTE_NOT_MATCHED = [];
    public const SENDING           = [];
    public const TERMINATED        = [];

    /** @var array<string, mixed> */
    public static array $defaults = [
        'before'          => self::BEFORE,
        'dispatched'      => self::DISPATCHED,
        'exception'       => self::EXCEPTION,
        'routeMatched'    => self::ROUTE_MATCHED,
        'routeNotMatched' => self::ROUTE_NOT_MATCHED,
        'sending'         => self::SENDING,
        'terminated'      => self::TERMINATED,
    ];
}
