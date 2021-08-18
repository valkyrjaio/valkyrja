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
     * The uri to redirect to.
     *
     * @var string
     */
    protected string $uri;

    /**
     * RedirectResponse constructor.
     *
     * @param string|null $uri        [optional] The uri
     * @param int|null    $statusCode [optional] The status
     * @param array|null  $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(string $uri = null, int $statusCode = null, array $headers = null)
    {
        $this->uri = $uri ?? '/';

        parent::__construct(
            null,
            $statusCode ?? StatusCode::FOUND,
            $this->injectHeader(Header::LOCATION, $this->uri, $headers, true)
        );
    }

    /**
     * Get the uri.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Set the uri.
     *
     * @param string $uri The uri
     *
     * @return static
     */
    public function setUri(string $uri): self
    {
        // Set the uri
        $this->uri = $uri;

        // Set the location header for the redirect
        return $this->withHeader(Header::LOCATION, $uri);
    }

    /**
     * Set the redirect uri to secure.
     *
     * @param string  $path    The path
     * @param Request $request The request
     *
     * @return static
     */
    public function secure(string $path, Request $request): self
    {
        // Set the uri to https with the host and path
        $this->setUri('https://' . $request->getUri()->getHostPort() . $path);

        return $this;
    }

    /**
     * Redirect back to the referer.
     *
     * @param Request $request The request
     *
     * @return static
     */
    public function back(Request $request): self
    {
        $refererUri = $request->getHeaderLine('Referer');

        $this->setUri($refererUri ?: '/');

        return $this;
    }

    /**
     * Throw this redirect.
     *
     * @throws HttpRedirectException
     *
     * @return void
     */
    public function throw(): void
    {
        throw new HttpRedirectException($this->statusCode, $this->uri, $this->getHeaders(), $this);
    }
}
