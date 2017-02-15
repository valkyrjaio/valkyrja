<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http;

/**
 * Interface RedirectResponse
 *
 * @package Valkyrja\Contracts\Http
 */
interface RedirectResponse extends Response
{
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
        int $status = Response::HTTP_FOUND,
        array $headers = [],
        string $uri = '/'
    );

    /**
     * Create a new redirect response.
     *
     * @param string $uri     [optional] The URI to redirect to
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Http\Exceptions\InvalidStatusCodeException
     */
    public static function createRedirect(
        string $uri = '/',
        int $status = Response::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse;

    /**
     * Get the uri.
     *
     * @return string
     */
    public function getUri():? string;

    /**
     * Set the uri.
     *
     * @param string $uri The uri
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    public function setUri(string $uri): RedirectResponse;
}
