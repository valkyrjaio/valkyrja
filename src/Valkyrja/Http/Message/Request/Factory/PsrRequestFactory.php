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

namespace Valkyrja\Http\Message\Request\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Factory\PsrUploadedFileFactory;
use Valkyrja\Http\Message\Header\Factory\PsrHeaderFactory;
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
        $uri = PsrUriFactory::fromPsr($psrRequest->getUri());

        $body = PsrStreamFactory::fromPsr($psrRequest->getBody());

        $files = PsrUploadedFileFactory::fromPsrArray(...$psrRequest->getUploadedFiles());

        return new ServerRequest(
            uri: $uri,
            method: RequestMethod::from($psrRequest->getMethod()),
            body: $body,
            headers: PsrHeaderFactory::fromPsr($psrRequest->getHeaders()),
            server: $psrRequest->getServerParams(),
            cookies: $psrRequest->getCookieParams(),
            query: $psrRequest->getQueryParams(),
            parsedBody: (array) $psrRequest->getParsedBody(),
            protocol: ProtocolVersion::from($psrRequest->getProtocolVersion()),
            files: $files
        );
    }
}
