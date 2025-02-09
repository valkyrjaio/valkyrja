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
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Uri\Contract\Uri;

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
     * @param Uri|null                     $uri        [optional] The uri to redirect to
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return static
     */
    public static function createFromUri(
        ?Uri $uri = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): static;

    /**
     * Get the uri.
     *
     * @return Uri
     */
    public function getUri(): Uri;

    /**
     * Set the uri.
     *
     * @param Uri $uri The uri
     *
     * @return static
     */
    public function withUri(Uri $uri): static;

    /**
     * Set the redirect uri to secure.
     *
     * @param string        $path    The path
     * @param ServerRequest $request The request
     *
     * @return static
     */
    public function secure(string $path, ServerRequest $request): static;

    /**
     * Redirect back to the referer.
     *
     * @param ServerRequest $request The request
     *
     * @return static
     */
    public function back(ServerRequest $request): static;

    /**
     * Throw this redirect.
     */
    public function throw(): void;
}
