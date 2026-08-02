<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri;

class HttpRedirectResponseException extends HttpResponseException
{
    /**
     * The uri to redirect to for this exception.
     *
     * @var UriContract
     */
    protected UriContract $uri;

    /**
     * @see http://php.net/manual/en/exception.construct.php
     */
    public function __construct(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null,
        ResponseContract|null $response = null
    ) {
        $statusCode ??= StatusCode::FOUND;
        $headers ??= new HeaderCollection();
        $uri ??= new Uri(path: '/');
        // Set a new redirect response if one wasn't passed in
        $response ??= RedirectResponse::createFromUri($uri, $statusCode, $headers);
        // Set the uri
        $this->uri = $uri;

        parent::__construct($statusCode, 'Redirect', $headers, $response);
    }

    /**
     * Get the uri to redirect to for this exception.
     */
    public function getUri(): UriContract
    {
        return $this->uri;
    }
}
