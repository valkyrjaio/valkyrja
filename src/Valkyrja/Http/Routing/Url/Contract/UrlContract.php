<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Url\Contract;

interface UrlContract
{
    /**
     * Get a route url by name.
     *
     * @param non-empty-string          $name The name of the route to get
     * @param array<string, string|int> $data [optional] The route data if dynamic
     */
    public function getUrl(string $name, array $data): string;
}
