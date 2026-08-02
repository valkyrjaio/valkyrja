<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Response\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Uri\Contract\UriContract;

interface RedirectResponseContract extends ResponseContract
{
    /**
     * Create a redirect response.
     */
    public static function createFromUri(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): static;

    /**
     * Get the uri.
     */
    public function getUri(): UriContract;

    /**
     * Set the uri.
     *
     * @param UriContract $uri The uri
     */
    public function withUri(UriContract $uri): static;

    /**
     * Set the redirect uri to secure.
     *
     * @param string                $path    The path
     * @param ServerRequestContract $request The request
     */
    public function secure(string $path, ServerRequestContract $request): static;

    /**
     * Redirect back to the referer.
     *
     * @param ServerRequestContract $request The request
     */
    public function back(ServerRequestContract $request): static;
}
