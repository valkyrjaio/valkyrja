<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Responses;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\Header;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Exceptions\InvalidStatusCode;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\RedirectResponse as RedirectResponseContract;

/**
 * Class NativeRedirectResponse.
 *
 * @author Melech Mizrachi
 */
class RedirectResponse extends Response implements RedirectResponseContract
{
    /**
     * The uri to redirect to.
     *
     * @var string
     */
    protected string $uri;

    /**
     * NativeRedirectResponse constructor.
     *
     * @param string     $uri     The uri
     * @param int|null   $status  [optional] The status
     * @param array|null $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStatusCode
     * @throws InvalidStream
     */
    public function __construct(string $uri = '/', int $status = null, array $headers = [])
    {
        $this->uri = $uri;

        parent::__construct(
            null,
            $status ?? StatusCode::FOUND,
            $this->injectHeader(Header::LOCATION, $uri, $headers, true)
        );
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RedirectResponseContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(RedirectResponseContract::class, new static());
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
    public function secure(string $path = null): self
    {
        // If not path was set
        if (null === $path) {
            // If the uri is already set
            $path = $this->uri ?? request()->getPath();
        }

        // If the path doesn't start with a /
        if ('/' !== $path[0]) {
            // Set the uri as the path
            $this->setUri($path);

            // Return out of the method
            return $this;
        }

        // Set the uri to https with the host and path
        $this->setUri('https://' . request()->getHttpHost() . $path);

        return $this;
    }

    /**
     * Redirect back to the referer.
     *
     * @return static
     */
    public function back(): self
    {
        $refererUri = request()->headers()->get('Referer');

        // Ensure the route being redirected to is a valid internal route
        if (! router()->isInternalUri($refererUri)) {
            // If not set as the index
            $refererUri = '/';
        }

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
        throw new HttpRedirectException($this->statusCode, $this->uri, null, $this->getHeaders());
    }
}
