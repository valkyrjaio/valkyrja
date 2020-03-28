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

namespace Valkyrja\Http\Requests;

use InvalidArgumentException;
use Valkyrja\Http\Exceptions\InvalidMethod;
use Valkyrja\Http\Exceptions\InvalidPath;
use Valkyrja\Http\Exceptions\InvalidPort;
use Valkyrja\Http\Exceptions\InvalidProtocolVersion;
use Valkyrja\Http\Exceptions\InvalidQuery;
use Valkyrja\Http\Exceptions\InvalidScheme;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\SimpleRequest as SimpleRequestContract;
use Valkyrja\Http\Stream;
use Valkyrja\Http\Uri;

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
     * @param Uri|null    $uri     [optional] The uri
     * @param string|null $method  [optional] The method
     * @param Stream|null $body    [optional] The body stream
     * @param array|null  $headers [optional] The headers
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
