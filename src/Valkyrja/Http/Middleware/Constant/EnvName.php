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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string REQUEST_RECEIVED  = 'HTTP_MIDDLEWARE_REQUEST_RECEIVED';
    public const string ROUTE_DISPATCHED  = 'HTTP_MIDDLEWARE_ROUTE_DISPATCHED';
    public const string ROUTE_MATCHED     = 'HTTP_MIDDLEWARE_ROUTE_MATCHED';
    public const string ROUTE_NOT_MATCHED = 'HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED';
    public const string THROWABLE_CAUGHT  = 'HTTP_MIDDLEWARE_THROWABLE_CAUGHT';
    public const string SENDING_RESPONSE  = 'HTTP_MIDDLEWARE_SENDING_RESPONSE';
    public const string TERMINATED        = 'HTTP_MIDDLEWARE_TERMINATED';
}
