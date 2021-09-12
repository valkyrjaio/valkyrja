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
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Constants\StreamType;
use Valkyrja\Http\Exceptions\InvalidMethod;
use Valkyrja\Http\Exceptions\InvalidPath;
use Valkyrja\Http\Exceptions\InvalidPort;
use Valkyrja\Http\Exceptions\InvalidProtocolVersion;
use Valkyrja\Http\Exceptions\InvalidQuery;
use Valkyrja\Http\Exceptions\InvalidRequestTarget;
use Valkyrja\Http\Exceptions\InvalidScheme;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\Messages\MessageTrait;
use Valkyrja\Http\Stream;
use Valkyrja\Http\Streams\Stream as HttpStream;
use Valkyrja\Http\Uri;
use Valkyrja\Http\Uris\Uri as HttpUri;

use function in_array;
use function preg_match;
use function sprintf;

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
trait RequestTrait
{
    use MessageTrait;

    public static string $HOST_NAME = 'Host';
    public static string $HOST_NAME_NORM = 'host';

    /**
     * The uri.
     *
     * @var Uri
     */
    protected Uri $uri;

    /**
     * The method.
     *
     * @var string
     */
    protected string $method;

    /**
     * The request target.
     *
     * @var string|null
     */
    protected ?string $requestTarget = null;

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        if (null !== $this->requestTarget) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();

        if ($this->uri->getQuery()) {
            $target .= '?' . $this->uri->getQuery();
        }

        if (empty($target)) {
            $target = '/';
        }

        return $target;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): self
    {
        $this->validateRequestTarget($requestTarget);

        $new = clone $this;

        $new->requestTarget = $requestTarget;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): self
    {
        $this->validateMethod($method);

        $new = clone $this;

        $new->method = $method;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withUri(Uri $uri, bool $preserveHost = false): self
    {
        $new = clone $this;

        $new->uri = $uri;

        if ($preserveHost && $this->hasHeader(static::$HOST_NAME)) {
            return $new;
        }

        if (! $uri->getHost()) {
            return $new;
        }

        $host = $uri->getHost();

        $new->headerNames[static::$HOST_NAME_NORM] = static::$HOST_NAME;

        $new->headers = $this->injectHeader(static::$HOST_NAME, $host, $new->headerNames, true);

        return $new;
    }

    /**
     * Validate a request target.
     *
     * @param string $requestTarget The request target
     *
     * @throws InvalidRequestTarget
     *
     * @return void
     */
    protected function validateRequestTarget(string $requestTarget): void
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidRequestTarget('Invalid request target provided; cannot contain whitespace');
        }
    }

    /**
     * Validate a method.
     *
     * @param string $method The method
     *
     * @throws InvalidMethod
     *
     * @return void
     */
    protected function validateMethod(string $method): void
    {
        if (! in_array($method, RequestMethod::ANY, true)) {
            throw new InvalidMethod(sprintf('Unsupported HTTP method "%s" provided', $method));
        }
    }

    /**
     * Initialize the Request.
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
     *
     * @return void
     */
    protected function initialize(
        Uri $uri = null,
        string $method = null,
        Stream $body = null,
        array $headers = null
    ): void {
        $this->uri     = $uri ?? new HttpUri();
        $this->method  = $method ?? RequestMethod::GET;
        $this->stream  = $body ?? new HttpStream(StreamType::INPUT);
        $this->headers = $headers ?? [];

        $this->setHeaders($this->headers);
        $this->validateMethod($this->method);
        $this->validateProtocolVersion($this->protocol);

        if ($this->hasHeader(static::$HOST_NAME) && $this->uri->getHost()) {
            $this->headerNames[static::$HOST_NAME_NORM] = static::$HOST_NAME;
            $this->headers[static::$HOST_NAME]          = [
                $this->uri->getHost(),
            ];
        }
    }
}
