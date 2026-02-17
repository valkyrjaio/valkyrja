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

namespace Valkyrja\Http\Middleware\Handler\Enum;

enum MiddlewareType
{
    case REQUEST_RECEIVED;
    case ROUTE_DISPATCHED;
    case ROUTE_MATCHED;
    case ROUTE_NOT_MATCHED;
    case SENDING_RESPONSE;
    case TERMINATED;
    case THROWABLE_CAUGHT;
}
