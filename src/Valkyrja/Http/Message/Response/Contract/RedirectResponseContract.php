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

namespace Valkyrja\Http\Message\Response\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Uri\Contract\UriContract;

/**
 * Interface RedirectResponseContract.
 */
interface RedirectResponseContract extends ResponseContract
{
    /**
     * Create a redirect response.
     *
     * @param UriContract|null             $uri        [optional] The uri to redirect to
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return static
     */
    public static function createFromUri(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static;

    /**
     * Get the uri.
     *
     * @return UriContract
     */
    public function getUri(): UriContract;

    /**
     * Set the uri.
     *
     * @param UriContract $uri The uri
     *
     * @return static
     */
    public function withUri(UriContract $uri): static;

    /**
     * Set the redirect uri to secure.
     *
     * @param string                $path    The path
     * @param ServerRequestContract $request The request
     *
     * @return static
     */
    public function secure(string $path, ServerRequestContract $request): static;

    /**
     * Redirect back to the referer.
     *
     * @param ServerRequestContract $request The request
     *
     * @return static
     */
    public function back(ServerRequestContract $request): static;

    /**
     * Throw this redirect.
     */
    public function throw(): void;
}
