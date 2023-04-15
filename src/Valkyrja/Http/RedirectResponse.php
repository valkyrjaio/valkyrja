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

namespace Valkyrja\Http;

/**
 * Interface RedirectResponse.
 *
 * @author Melech Mizrachi
 */
interface RedirectResponse extends Response
{
    /**
     * Create a redirect response.
     *
     * @param string|null $uri        [optional] The uri to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return static
     */
    public static function createFromUri(string $uri = null, int $statusCode = null, array $headers = null): static;

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
     * @return static
     */
    public function setUri(string $uri): static;

    /**
     * Set the redirect uri to secure.
     *
     * @param string  $path    The path
     * @param Request $request The request
     *
     * @return static
     */
    public function secure(string $path, Request $request): static;

    /**
     * Redirect back to the referer.
     *
     * @param Request $request The request
     *
     * @return static
     */
    public function back(Request $request): static;

    /**
     * Throw this redirect.
     */
    public function throw(): void;
}
