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
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;
use Valkyrja\Http\Routing\Url\Contract\Url as Contract;

use function str_replace;
use function strlen;
use function strpos;
use function substr;

/**
 * Class Url.
 *
 * @author Melech Mizrachi
 */
class Url implements Contract
{
    /**
     * Url constructor.
     */
    public function __construct(
        protected ServerRequest $request,
        protected Collection $collection,
        protected Matcher $matcher
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRouteNameException
     */
    #[Override]
    public function getUrl(string $name, array|null $data = null, bool|null $absolute = null): string
    {
        // Get the matching route
        $route = $this->collection->getRouteByName($name);

        if ($route === null) {
            throw new InvalidRouteNameException("$name is not a valid named route");
        }

        $host = $absolute
            ? $this->routeHost($route)
            : '';
        // Get the path from the generator
        $path = $route->getPath();

        // If any data was passed
        if ($data !== null) {
            // Iterate through the data and replace it in the path
            foreach ($data as $datumName => $datum) {
                $path = str_replace('{' . $datumName . '}', (string) $datum, $path);
            }
        }

        return $host . $path;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRouteByPath(string $path, RequestMethod|null $method = null): Route|null
    {
        return $this->matcher->match($path, $method ?? RequestMethod::GET);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isInternalUri(string $uri): bool
    {
        // Replace the scheme if it exists
        $uri = str_replace(['http://', 'https://'], '', $uri);

        // Get the host of the uri
        $host = substr($uri, 0, (int) strpos($uri, '/'));

        // If the host does not match the current request uri's host
        if ($host && $host !== $this->request->getUri()->getHost()) {
            // Return false immediately
            return false;
        }

        /** @var non-empty-string $uri */
        // Get only the path (full string from the first slash to the end of the path)
        $uri = substr($uri, (int) strpos($uri, '/'), strlen($uri));

        // Try to match the route
        $route = $this->getRouteByPath($uri);

        return $route !== null;
    }

    /**
     * Get a route's host.
     *
     * @param Route $route The route
     *
     * @return string
     */
    protected function routeHost(Route $route): string
    {
        return 'https'
            . '://'
            . $this->request->getUri()->getHostPort();
    }
}
