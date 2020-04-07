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
use Valkyrja\Container\Container;
use Valkyrja\Http\Enums\Header;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\RedirectResponse as Contract;

use function Valkyrja\request;

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
        parent::__construct();

        $this->initializeRedirect($uri, $statusCode, $headers);
    }

    /**
     * Initialize a redirect response.
     *
     * @param string|null $uri     [optional] The uri
     * @param int|null    $status  [optional] The status
     * @param array|null  $headers [optional] The headers
     *
     * @return void
     */
    protected function initializeRedirect(string $uri = null, int $status = null, array $headers = null): void
    {
        $this->uri = $uri ?? '/';

        parent::__construct(
            null,
            $status ?? StatusCode::FOUND,
            $this->injectHeader(Header::LOCATION, $this->uri, $headers, true)
        );
    }

    /**
     * Create a redirect response.
     *
     * @param string|null $uri     [optional] The uri
     * @param int|null    $status  [optional] The status
     * @param array|null  $headers [optional] The headers
     *
     * @return static
     */
    public static function createRedirectResponse(string $uri = null, int $status = null, array $headers = null): self
    {
        $response = new static();

        $response->initializeRedirect($uri, $status, $headers);

        return $response;
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
     * @param string $path The path
     *
     * @return static
     */
    public function secure(string $path): self
    {
        // If the path doesn't start with a /
        if ('/' !== $path[0]) {
            // Set the uri as the path
            $this->setUri($path);

            // Return out of the method
            return $this;
        }

        // Set the uri to https with the host and path
        $this->setUri('https://' . request()->getUri()->getHostPort() . $path);

        return $this;
    }

    /**
     * Redirect back to the referer.
     *
     * @return static
     */
    public function back(): self
    {
        $refererUri = request()->getHeaderLine('Referer');

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

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $container->setSingleton(
            Contract::class,
            new static()
        );
    }
}
