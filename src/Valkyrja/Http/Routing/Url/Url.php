<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Url;

use Override;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRouteNameException;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;

use function str_replace;

class Url implements UrlContract
{
    public function __construct(
        protected RouteCollectionContract $collection,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws HttpRoutingInvalidRouteNameException
     */
    #[Override]
    public function getUrl(string $name, array $data): string
    {
        // Get the matching route
        $route = $this->collection->getByName($name);

        // Get the path from the generator
        $path = $route->getPath();

        // Iterate through the data and replace it in the path
        foreach ($data as $datumName => $datum) {
            $path = str_replace('{' . $datumName . '}', (string) $datum, $path);
        }

        return $path;
    }
}
