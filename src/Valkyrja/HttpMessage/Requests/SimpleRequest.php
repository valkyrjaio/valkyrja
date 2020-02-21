<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Requests;

use InvalidArgumentException;
use Valkyrja\HttpMessage\Exceptions\InvalidMethod;
use Valkyrja\HttpMessage\Exceptions\InvalidPath;
use Valkyrja\HttpMessage\Exceptions\InvalidPort;
use Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion;
use Valkyrja\HttpMessage\Exceptions\InvalidQuery;
use Valkyrja\HttpMessage\Exceptions\InvalidScheme;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\SimpleRequest as SimpleRequestContract;
use Valkyrja\HttpMessage\Stream;
use Valkyrja\HttpMessage\Uri;

/**
 * Representation of an outgoing, client-side request.
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 * During construction, implementations MUST attempt to set the Host header from
 * a provided URI if no Host header is provided.
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @author Melech Mizrachi
 */
class SimpleRequest implements SimpleRequestContract
{
    use RequestTrait;

    /**
     * NativeRequest constructor.
     *
     * @param Uri    $uri     [optional] The uri
     * @param string $method  [optional] The method
     * @param Stream $body    [optional] The body stream
     * @param array  $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidMethod
     * @throws InvalidPath
     * @throws InvalidPort
     * @throws InvalidProtocolVersion
     * @throws InvalidQuery
     * @throws InvalidScheme
     * @throws InvalidStream
     */
    public function __construct(Uri $uri = null, string $method = null, Stream $body = null, array $headers = null)
    {
        $this->initialize($uri, $method, $body, $headers);
    }
}
