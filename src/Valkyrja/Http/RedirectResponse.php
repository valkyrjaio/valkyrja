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

/**
 * Interface RedirectResponse.
 *
 * @author Melech Mizrachi
 */
interface RedirectResponse extends Response
{
    /**
     * Create a new redirect response.
     *
     * @param string $uri     [optional] The URI to redirect to
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public static function createRedirect(
        string $uri = null,
        int $status = StatusCode::FOUND,
        array $headers = []
    ): self;

    /**
     * Get the uri.
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * Set the uri.
     *
     * @param string $uri The uri
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function setUri(string $uri): self;

    /**
     * Set the redirect uri to secure.
     *
     * @param string $path The path
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function secure(string $path = null): self;

    /**
     * Redirect back to the referer.
     *
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function back(): self;

    /**
     * Throw this redirect.
     */
    public function throw(): void;
}
