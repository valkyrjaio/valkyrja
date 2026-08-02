<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Factory\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;

interface RoutingResponseFactoryContract
{
    /**
     * Redirect to a named route response builder.
     *
     * @param non-empty-string          $name The name of the route
     * @param array<string, string|int> $data [optional] The data for dynamic routes
     */
    public function createRouteRedirectResponse(
        string $name,
        array $data = [],
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): RedirectResponseContract;
}
