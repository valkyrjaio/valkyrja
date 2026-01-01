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

namespace Valkyrja\Http\Message\Factory;

use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;

use function array_key_exists;
use function preg_match;
use function sprintf;

/**
 * Abstract Class RequestFactory.
 *
 * @author Melech Mizrachi
 */
abstract class RequestFactory
{
    /**
     * Create a request from the supplied superglobal values.
     *
     * @param array<string, string>|null      $server  [optional] $_SERVER superglobal
     * @param array<array-key, mixed>|null    $query   [optional] $_GET superglobal
     * @param array<array-key, mixed>|null    $body    [optional] $_POST superglobal
     * @param array<string, string|null>|null $cookies [optional] $_COOKIE superglobal
     * @param array<array-key, mixed>|null    $files   [optional] $_FILES superglobal
     * @param class-string<ServerRequest>     $class   [optional] The request class to return
     *
     * @return ServerRequest
     */
    public static function fromGlobals(
        array|null $server = null,
        array|null $query = null,
        array|null $body = null,
        array|null $cookies = null,
        array|null $files = null,
        string $class = ServerRequest::class
    ): ServerRequest {
        $files ??= $_FILES;
        $server ??= $_SERVER;
        $query ??= $_GET;
        $body ??= $_POST;

        /** @var array<string, string> $server */
        $server['REQUEST_METHOD'] ??= RequestMethod::GET->value;

        $server  = ServerFactory::normalizeServer($server);
        $headers = HeaderFactory::marshalHeaders($server);

        if (! empty($files)) {
            $files = UploadedFileFactory::normalizeFiles($files);
        }

        if ($cookies === null && array_key_exists('cookie', $headers)) {
            $cookies = CookieFactory::parseCookieHeader($headers['cookie'][0]);
        }

        $cookies ??= $_COOKIE;

        /** @var array<string, string|null> $cookies */
        /** @var array<string, UploadedFileContract> $files */

        return new $class(
            uri: UriFactory::marshalUriFromServer($server, $headers),
            method: RequestMethod::from($server['REQUEST_METHOD']),
            body: new Stream(stream: PhpWrapper::input),
            headers: $headers,
            server: $server,
            cookies: $cookies,
            query: $query,
            parsedBody: $body,
            protocol: static::getProtocolVersionFromServer($server),
            files: $files,
        );
    }

    /**
     * Create a json request.
     *
     * @param array<string, string>|null      $server  [optional] $_SERVER superglobal
     * @param array<array-key, mixed>|null    $query   [optional] $_GET superglobal
     * @param array<array-key, mixed>|null    $body    [optional] $_POST superglobal
     * @param array<string, string|null>|null $cookies [optional] $_COOKIE superglobal
     * @param array<array-key, mixed>|null    $files   [optional] $_FILES superglobal
     *
     * @return ServerRequest
     */
    public static function jsonFromGlobals(
        array|null $server = null,
        array|null $query = null,
        array|null $body = null,
        array|null $cookies = null,
        array|null $files = null
    ): ServerRequest {
        return self::fromGlobals(
            server: $server,
            query: $query,
            body: $body,
            cookies: $cookies,
            files: $files,
            class: JsonServerRequest::class
        );
    }

    /**
     * Get a ServerRequest from a PSR ServerRequestInterface object.
     *
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    public static function fromPsr(ServerRequestInterface $psrRequest): ServerRequest
    {
        $uri = UriFactory::fromPsr($psrRequest->getUri());

        $body = StreamFactory::fromPsr($psrRequest->getBody());

        $files = UploadedFileFactory::fromPsrArray(...$psrRequest->getUploadedFiles());

        return new ServerRequest(
            uri: $uri,
            method: RequestMethod::from($psrRequest->getMethod()),
            body: $body,
            headers: $psrRequest->getHeaders(),
            server: $psrRequest->getServerParams(),
            cookies: $psrRequest->getCookieParams(),
            query: $psrRequest->getQueryParams(),
            parsedBody: (array) $psrRequest->getParsedBody(),
            protocol: ProtocolVersion::from($psrRequest->getProtocolVersion()),
            files: $files
        );
    }

    /**
     * Return HTTP protocol version (X.Y).
     *
     * @param array<string, string> $server
     *
     * @throws UnexpectedValueException
     *
     * @return ProtocolVersion
     */
    protected static function getProtocolVersionFromServer(array $server): ProtocolVersion
    {
        $serverProtocol = $server['SERVER_PROTOCOL'] ?? null;

        if ($serverProtocol === null) {
            return ProtocolVersion::V1_1;
        }

        if (! preg_match('#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#', $serverProtocol, $matches)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Unrecognized protocol version (%s)',
                    $serverProtocol
                )
            );
        }

        return ProtocolVersion::from($matches['version']);
    }
}
