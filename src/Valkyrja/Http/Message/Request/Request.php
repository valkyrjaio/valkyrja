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

namespace Valkyrja\Http\Message\Request;

use Override;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Request\Contract\RequestContract as Contract;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidRequestTargetException;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream as HttpStream;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Trait\Message;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;

use function preg_match;

/**
 * Class Request.
 *
 * @author Melech Mizrachi
 */
class Request implements Contract
{
    use Message;

    /**
     * The request target.
     *
     * @var string|null
     */
    protected string|null $requestTarget = null;

    /**
     * Request constructor.
     *
     * @param UriContract             $uri     [optional] The uri
     * @param RequestMethod           $method  [optional] The method
     * @param StreamContract          $body    [optional] The body stream
     * @param array<string, string[]> $headers [optional] The headers
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected UriContract $uri = new HttpUri(),
        protected RequestMethod $method = RequestMethod::GET,
        StreamContract $body = new HttpStream(PhpWrapper::input),
        array $headers = []
    ) {
        $this->setBody($body);
        $this->setHeaders($headers);
        $this->addHostHeaderFromUri();
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
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
    #[Override]
    public function getMethod(): RequestMethod
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withMethod(RequestMethod $method): static
    {
        $new = clone $this;

        $new->method = $method;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUri(): UriContract
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUri(UriContract $uri, bool $preserveHost = false): static
    {
        $new = clone $this;

        $new->uri = $uri;

        if ($preserveHost && $this->hasHeader(HeaderName::HOST)) {
            return $new;
        }

        if (! $uri->getHost()) {
            return $new;
        }

        $host = $new->getHostFromUri();

        $new->headerNames['host'] = HeaderName::HOST;

        $new->headers = $this->injectHeader(HeaderName::HOST, $host, $this->headers, true);

        return $new;
    }

    /**
     * Clone the object.
     */
    public function __clone()
    {
        $this->uri = clone $this->uri;

        $this->stream = clone $this->stream;
    }

    /**
     * Validate a request target.
     *
     * @param string $requestTarget The request target
     *
     * @throws InvalidRequestTargetException
     *
     * @return void
     */
    protected function validateRequestTarget(string $requestTarget): void
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidRequestTargetException('Invalid request target provided; cannot contain whitespace');
        }
    }

    /**
     * Retrieve the host from the URI instance.
     */
    protected function getHostFromUri(): string
    {
        $host = $this->uri->getHost();
        $port = $this->uri->getPort();
        $host .= $port !== null
            ? ':' . ((string) $port)
            : '';

        return $host;
    }

    /**
     * Add the host header from the URI.
     */
    protected function addHostHeaderFromUri(): void
    {
        if (! $this->hasHeader(HeaderName::HOST) && $this->uri->getHost()) {
            $this->headerNames['host']       = HeaderName::HOST;
            $this->headers[HeaderName::HOST] = [
                $this->getHostFromUri(),
            ];
        }
    }
}
