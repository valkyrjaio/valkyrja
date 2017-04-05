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

use Valkyrja\Http\ResponseCode;

/**
 * Interface RedirectResponse
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
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
     */
    public function __construct(
        string $content = '',
        int $status = ResponseCode::HTTP_FOUND,
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
     */
    public static function createRedirect(
        string $uri = '/',
        int $status = ResponseCode::HTTP_FOUND,
        array $headers = []
    ): self;

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
    public function setUri(string $uri): self;

    /**
     * Redirect back to the referer.
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    public function back(): self;
}
