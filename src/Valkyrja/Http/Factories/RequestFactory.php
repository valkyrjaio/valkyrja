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

namespace Valkyrja\Http\Factories;

use JsonException;
use UnexpectedValueException;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Constants\StreamType;
use Valkyrja\Http\Requests\Request;
use Valkyrja\Http\Streams\Stream;

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
     * If any argument is not supplied, the corresponding superglobal value will
     * be used.
     * The ServerRequest created is then passed to the fromServer() method in
     * order to marshal the request URI and headers.
     *
     * @param array|null $server  $_SERVER superglobal
     * @param array|null $query   $_GET superglobal
     * @param array|null $body    $_POST superglobal
     * @param array|null $cookies $_COOKIE superglobal
     * @param array|null $files   $_FILES superglobal
     *
     * @throws JsonException
     *
     * @return Request
     *
     * @see fromServer()
     */
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    ): Request {
        $server  = ServerFactory::normalizeServer($server ?? $_SERVER);
        $files   = FileFactory::normalizeFiles($files ?? $_FILES);
        $headers = HeaderFactory::marshalHeaders($server);

        if (null === $cookies && array_key_exists('cookie', $headers)) {
            $cookies = CookieFactory::parseCookieHeader($headers['cookie']);
        }

        return new Request(
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
