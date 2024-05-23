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
use Psr\Http\Message\UploadedFileInterface;
use UnexpectedValueException;
use Valkyrja\Http\Message\Constant\RequestMethod;
use Valkyrja\Http\Message\Constant\StreamType;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Uri;

use function array_key_exists;
use function array_walk;
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
     * @param array|null                  $server  [optional] $_SERVER superglobal
     * @param array|null                  $query   [optional] $_GET superglobal
     * @param array|null                  $body    [optional] $_POST superglobal
     * @param array|null                  $cookies [optional] $_COOKIE superglobal
     * @param array|null                  $files   [optional] $_FILES superglobal
     * @param class-string<ServerRequest> $class   [optional] The request class to return
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

        $server  = ServerFactory::normalizeServer($server ?? $_SERVER);
        $headers = HeaderFactory::marshalHeaders($server);

        if (! empty($files)) {
            $files = FileFactory::normalizeFiles($files);
        }

        if ($cookies === null && array_key_exists('cookie', $headers)) {
            $cookies = CookieFactory::parseCookieHeader($headers['cookie']);
        }

        return new $class(
            UriFactory::marshalUriFromServer($server, $headers),
            $server['REQUEST_METHOD'] ?? RequestMethod::GET,
            new Stream(StreamType::INPUT),
            $headers,
            $server,
            $cookies ?? $_COOKIE,
            $query ?? $_GET,
            $body ?? $_POST,
            static::getProtocolVersionFromServer($server),
            ...$files,
        );
    }

    /**
     * Create a json request.
     *
     * @param array|null $server  [optional] $_SERVER superglobal
     * @param array|null $query   [optional] $_GET superglobal
     * @param array|null $body    [optional] $_POST superglobal
     * @param array|null $cookies [optional] $_COOKIE superglobal
     * @param array|null $files   [optional] $_FILES superglobal
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
        return self::fromGlobals($server, $query, $body, $cookies, $files, JsonServerRequest::class);
    }

    /**
     * Get a ServerRequest from a PSR ServerRequestInterface object.
     */
    public static function fromPsr(ServerRequestInterface $psrRequest): ServerRequest
    {
        $psrUri = $psrRequest->getUri();
        $uri    = new Uri(
            scheme: $psrUri->getScheme(),
            userInfo: $psrUri->getUserInfo(),
            host: $psrUri->getHost(),
            port: $psrUri->getPort(),
            path: $psrUri->getPath(),
            query: $psrUri->getQuery(),
            fragment: $psrUri->getFragment(),
        );

        $psrBody = $psrRequest->getBody();
        $body    = new Stream($psrBody->getContents());

        $files = $psrRequest->getUploadedFiles();

        array_walk($files, fn (UploadedFileInterface $file) => new UploadedFile(
            size: (int) $file->getSize(),
            errorStatus: $file->getError(),
            stream: new Stream($file->getStream()->getContents()),
            fileName: $file->getClientFilename(),
            mediaType: $file->getClientMediaType(),
        ));

        return new ServerRequest(
            $uri,
            $psrRequest->getMethod(),
            $body,
            $psrRequest->getHeaders(),
            $psrRequest->getServerParams(),
            $psrRequest->getCookieParams(),
            $psrRequest->getQueryParams(),
            (array) $psrRequest->getParsedBody(),
            $psrRequest->getProtocolVersion(),
            ...$files
        );
    }

    /**
     * Return HTTP protocol version (X.Y).
     *
     * @param array $server
     *
     * @throws UnexpectedValueException
     *
     * @return string
     */
    protected static function getProtocolVersionFromServer(array $server): string
    {
        $serverProtocol = $server['SERVER_PROTOCOL'] ?? null;

        if ($serverProtocol === null) {
            return '1.1';
        }

        if (! preg_match('#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#', $serverProtocol, $matches)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Unrecognized protocol version (%s)',
                    $serverProtocol
                )
            );
        }

        return $matches['version'];
    }
}
