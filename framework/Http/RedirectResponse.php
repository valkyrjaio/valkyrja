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

use Valkyrja\Contracts\Http\RedirectResponse as RedirectResponseContract;
use Valkyrja\Http\Exceptions\InvalidStatusCodeException;

/**
 * Class RedirectResponse
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class RedirectResponse extends Response implements RedirectResponseContract
{
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
        int $status = ResponseCode::HTTP_FOUND,
        array $headers = [],
        string $uri = '/'
    )
    {
        // Use the parent constructor to setup the class
        parent::__construct($content, $status, $headers);

        // If the status code set is not a redirect code
        if (! $this->isRedirect()) {
            // Throw an invalid status code exception
            throw new InvalidStatusCodeException("Status code of \"{$status}\" is not allowed");
        }

        // Set the uri
        $this->setUri($uri);
    }

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
        int $status = ResponseCode::HTTP_FOUND,
        array $headers = []
    ): RedirectResponseContract
    {
        return new static('', $status, $headers, $uri);
    }

    /**
     * Get the uri.
     *
     * @return string
     */
    public function getUri():? string
    {
        return $this->uri;
    }

    /**
     * Set the uri.
     *
     * @param string $uri The uri
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    public function setUri(string $uri): RedirectResponseContract
    {
        // Set the uri
        $this->uri = $uri;

        // Set the location header for the redirect
        $this->headers()->set('Location', $this->uri);

        return $this;
    }
}
