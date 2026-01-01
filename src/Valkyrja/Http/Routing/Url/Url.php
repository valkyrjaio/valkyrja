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

namespace Valkyrja\Http\Routing\Url;

use Override;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Url\Contract\UrlContract as Contract;

use function str_replace;

class Url implements Contract
{
    /**
     * Url constructor.
     */
    public function __construct(
        protected CollectionContract $collection,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRouteNameException
     */
    #[Override]
    public function getUrl(string $name, array|null $data = null): string
    {
        // Get the matching route
        $route = $this->collection->getByName($name);

        if ($route === null) {
            throw new InvalidRouteNameException("$name is not a valid named route");
        }

        // Get the path from the generator
        $path = $route->getPath();

        // If any data was passed
        if ($data !== null) {
            // Iterate through the data and replace it in the path
            foreach ($data as $datumName => $datum) {
                $path = str_replace('{' . $datumName . '}', (string) $datum, $path);
            }
        }

        return $path;
    }
}
