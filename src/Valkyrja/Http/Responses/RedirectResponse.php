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

namespace Valkyrja\Http\Responses;

use InvalidArgumentException;
use Valkyrja\Http\Constants\Header;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\RedirectResponse as Contract;
use Valkyrja\Http\Request;

/**
 * Class RedirectResponse.
 *
 * @author Melech Mizrachi
 */
class RedirectResponse extends Response implements Contract
{
    /**
     * RedirectResponse constructor.
     *
     * @param string $uri        [optional] The uri
     * @param int    $statusCode [optional] The status
     * @param array  $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(protected string $uri = '/', int $statusCode = StatusCode::OK, array $headers = [])
    {
        parent::__construct(
            statusCode: $statusCode ?? StatusCode::FOUND,
            headers   : $this->injectHeader(Header::LOCATION, $uri, $headers, true)
        );
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function setUri(string $uri): self
    {
        // Set the uri
        $this->uri = $uri;

        // Set the location header for the redirect
        return $this->withHeader(Header::LOCATION, $uri);
    }

    /**
     * @inheritDoc
     */
    public function secure(string $path, Request $request): self
    {
        // Set the uri to https with the host and path
        $this->setUri('https://' . $request->getUri()->getHostPort() . $path);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function back(Request $request): self
    {
        $refererUri = $request->getHeaderLine('Referer');

        $this->setUri($refererUri ?: '/');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function throw(): void
    {
        throw new HttpRedirectException($this->statusCode, $this->uri, $this->getHeaders(), $this);
    }
}
