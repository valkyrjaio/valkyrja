<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Request\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Factory\PsrUploadedFileFactory;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Factory\PsrHeaderFactory;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Stream\Factory\PsrStreamFactory;
use Valkyrja\Http\Message\Uri\Factory\PsrUriFactory;

abstract class PsrRequestFactory
{
    /**
     * Get a ServerRequest from a PSR ServerRequestInterface object.
     *
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    public static function fromPsr(ServerRequestInterface $psrRequest): ServerRequest
    {
        $uri        = PsrUriFactory::fromPsr($psrRequest->getUri());
        $method     = RequestMethod::from($psrRequest->getMethod());
        $body       = PsrStreamFactory::fromPsr($psrRequest->getBody());
        $files      = PsrUploadedFileFactory::fromPsrArray($psrRequest->getUploadedFiles());
        $headers    = PsrHeaderFactory::fromPsr($psrRequest->getHeaders());
        $protocol   = ProtocolVersion::from($psrRequest->getProtocolVersion());
        $server     = ServerParamCollection::fromArray($psrRequest->getServerParams());
        $cookies    = CookieParamCollection::fromArray($psrRequest->getCookieParams());
        $query      = QueryParamCollection::fromArray($psrRequest->getQueryParams());
        $parsedBody = ParsedBodyParamCollection::fromArray((array) $psrRequest->getParsedBody());

        return new ServerRequest(
            uri: $uri,
            method: $method,
            body: $body,
            headers: HeaderCollection::fromArray($headers),
            protocol: $protocol,
            server: $server,
            cookies: $cookies,
            query: $query,
            parsedBody: $parsedBody,
            files: $files
        );
    }
}
