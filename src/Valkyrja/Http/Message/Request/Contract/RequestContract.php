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

namespace Valkyrja\Http\Message\Request\Contract;

use InvalidArgumentException;
use Valkyrja\Http\Message\Contract\MessageContract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Uri\Contract\UriContract;

interface RequestContract extends MessageContract
{
    /**
     * Get the request target.
     */
    public function getRequestTarget(): string;

    /**
     * Create a new instance with the specified request target.
     *
     * @see http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     */
    public function withRequestTarget(string $requestTarget): static;

    /**
     * Get the HTTP method of the request.
     */
    public function getMethod(): RequestMethod;

    /**
     * Create a new instance with the specified HTTP method.
     *
     * @throws InvalidArgumentException for invalid HTTP methods
     */
    public function withMethod(RequestMethod $method): static;

    /**
     * Get the Uri for the request.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function getUri(): UriContract;

    /**
     * Create a new instance with the specified Uri.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function withUri(UriContract $uri, bool $preserveHost = false): static;
}
