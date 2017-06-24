<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Application;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Exceptions\InvalidStatusCodeException;
use Valkyrja\Support\Providers\Provides;

/**
 * Class RedirectResponse.
 *
 * @author Melech Mizrachi
 */
class RedirectResponseImpl extends ResponseImpl implements RedirectResponse
{
    use Provides;

    /**
     * The uri to redirect to.
     *
     * @var string
     */
    protected $uri;

    /**
     * RedirectResponse constructor.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     * @param string $uri     [optional] The URI to redirect to
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Http\Exceptions\InvalidStatusCodeException
     */
    public function __construct(
        string $content = '',
        int $status = StatusCode::FOUND,
        array $headers = [],
        string $uri = null
    ) {
        // Use the parent constructor to setup the class
        parent::__construct($content, $status, $headers);

        // If the status code set is not a redirect code
        if (! $this->isRedirect()) {
            // Throw an invalid status code exception
            throw new InvalidStatusCodeException(
                "Status code of \"{$status}\" is not allowed"
            );
        }

        if (null !== $uri) {
            // Set the uri
            $this->setUri($uri);
        }
    }

    /**
     * Create a new redirect response.
     *
     * @param string $uri     [optional] The URI to redirect to
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Http\Exceptions\InvalidStatusCodeException
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public static function createRedirect(
        string $uri = null,
        int $status = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        return new static('', $status, $headers, $uri);
    }

    /**
     * Get the uri.
     *
     * @return string
     */
    public function getUri(): string
    {
        if (null === $this->uri) {
            $this->setUri('/');
        }

        return $this->uri;
    }

    /**
     * Set the uri.
     *
     * @param string $uri The uri
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function setUri(string $uri): RedirectResponse
    {
        // Set the uri
        $this->uri = $uri;

        // Set the location header for the redirect
        $this->headers()->set('Location', $this->uri);

        return $this;
    }

    /**
     * Set the redirect uri to secure.
     *
     * @param string $path The path
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function secure(string $path = null): RedirectResponse
    {
        // If not path was set
        if (null === $path) {
            // If the uri is already set
            if (null !== $this->uri) {
                // Set the path to it
                $path = $this->uri;
            } else {
                // Otherwise set the path to the current path w/ query string
                $path = request()->getPath();
            }
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
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function back(): RedirectResponse
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
     * @throws \Valkyrja\Http\Exceptions\HttpRedirectException
     */
    public function throw(): void
    {
        throw new HttpRedirectException(
            $this->statusCode,
            $this->uri,
            null,
            $this->headers->all()
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
            RedirectResponse::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            RedirectResponse::class,
            new static()
        );
    }
}
