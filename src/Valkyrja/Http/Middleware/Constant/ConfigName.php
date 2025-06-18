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
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string REQUEST_RECEIVED  = 'requestReceived';
    public const string ROUTE_DISPATCHED  = 'routeDispatched';
    public const string ROUTE_MATCHED     = 'routeMatched';
    public const string ROUTE_NOT_MATCHED = 'routeNotMatched';
    public const string THROWABLE_CAUGHT  = 'throwableCaught';
    public const string SENDING_RESPONSE  = 'sendingResponse';
    public const string TERMINATED        = 'terminated';
}
