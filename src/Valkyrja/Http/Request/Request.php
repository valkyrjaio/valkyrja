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

namespace Valkyrja\Http\Request;

use Valkyrja\Http\Constant\RequestMethod;
use Valkyrja\Http\Constant\StreamType;
use Valkyrja\Http\Exception\InvalidArgumentException;
use Valkyrja\Http\Exception\InvalidMethod;
use Valkyrja\Http\Exception\InvalidRequestTarget;
use Valkyrja\Http\Message;
use Valkyrja\Http\Request\Contract\Request as SimpleRequestContract;
use Valkyrja\Http\Stream\Contract\Stream;
use Valkyrja\Http\Stream\Stream as HttpStream;
use Valkyrja\Http\Uri\Contract\Uri;
use Valkyrja\Http\Uri\Uri as HttpUri;

use function in_array;
use function preg_match;
use function sprintf;

/**
 * Class SimpleRequest.
 *
 * @author Melech Mizrachi
 */
class Request implements SimpleRequestContract
{
    use Message;

    public static string $HOST_NAME      = 'Host';
    public static string $HOST_NAME_NORM = 'host';

    /**
     * The request target.
     *
     * @var string|null
     */
    protected string|null $requestTarget = null;

    /**
     * SimpleRequest constructor.
     *
     * @param Uri    $uri     [optional] The uri
     * @param string $method  [optional] The method
     * @param Stream $body    [optional] The body stream
     * @param array  $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected Uri $uri = new HttpUri(),
        protected string $method = RequestMethod::GET,
        Stream $body = new HttpStream(StreamType::INPUT),
        array $headers = []
    ) {
        $this->setBody($body);
        $this->setHeaders($headers);
        $this->validateMethod($method);
        $this->validateProtocolVersion($this->protocol);

        if ($this->hasHeader(static::$HOST_NAME) && $this->uri->getHost()) {
            $this->headerNames[static::$HOST_NAME_NORM] = static::$HOST_NAME;
            $this->headers[static::$HOST_NAME]          = [
                $this->uri->getHost(),
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== null) {
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
    public function withRequestTarget(string $requestTarget): static
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
    public function withMethod(string $method): static
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
    public function withUri(Uri $uri, bool $preserveHost = false): static
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
}
