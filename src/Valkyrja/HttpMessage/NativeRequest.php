<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

/**
 * Representation of an outgoing, client-side request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * During construction, implementations MUST attempt to set the Host header from
 * a provided URI if no Host header is provided.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @author Melech Mizrachi
 */
class NativeRequest implements Request
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
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidMethod
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPath
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPort
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidQuery
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidScheme
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     */
    public function __construct(Uri $uri = null, string $method = null, Stream $body = null, array $headers = null)
    {
        $this->initialize($uri, $method, $body, $headers);
    }
}
