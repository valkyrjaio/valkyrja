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

namespace Valkyrja\Http\Message\Request\Psr;

use Override;
use Psr\Http\Message\ServerRequestInterface;
use Valkyrja\Http\Message\File\Factory\PsrUploadedFileFactory;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

/**
 * @property ServerRequestContract $request
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    public function __construct(ServerRequestContract $request)
    {
        parent::__construct($request);
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, mixed>
     */
    #[Override]
    public function getServerParams(): array
    {
        return $this->request->getServerParams()->getAll();
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, mixed>
     */
    #[Override]
    public function getCookieParams(): array
    {
        return $this->request->getCookieParams()->getAll();
    }

    /**
     * @inheritDoc
     *
     * @param array<array-key, mixed> $cookies The cookies
     */
    #[Override]
    public function withCookieParams(array $cookies): static
    {
        $new = clone $this;

        /** @var array<string, string|null> $cookies */
        $new->request = $this->request->withCookieParams(
            CookieParamCollection::fromArray($cookies)
        );

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, mixed>
     */
    #[Override]
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams()->getAll();
    }

    /**
     * @inheritDoc
     *
     * @param array<array-key, mixed> $query The query
     */
    #[Override]
    public function withQueryParams(array $query): static
    {
        $new = clone $this;

        $new->request = $this->request->withQueryParams(
            QueryParamCollection::fromArray($query)
        );

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, mixed>
     */
    #[Override]
    public function getUploadedFiles(): array
    {
        return PsrUploadedFileFactory::toPsrArray($this->request->getUploadedFiles());
    }

    /**
     * @inheritDoc
     *
     * @param array<array-key, mixed> $uploadedFiles The uploaded files
     */
    #[Override]
    public function withUploadedFiles(array $uploadedFiles): static
    {
        $new = clone $this;

        $new->request = $this->request->withUploadedFiles(
            PsrUploadedFileFactory::fromPsrArray($uploadedFiles)
        );

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, mixed>|object|null
     */
    #[Override]
    public function getParsedBody(): object|array|null
    {
        return $this->request->getParsedBody()->getAll();
    }

    /**
     * @inheritDoc
     *
     * @param array<array-key, mixed>|object|null $data The data
     */
    #[Override]
    public function withParsedBody($data): static
    {
        $new = clone $this;

        $new->request = $this->request->withParsedBody(
            ParsedBodyParamCollection::fromArray($data !== null ? (array) $data : [])
        );

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, mixed>
     */
    #[Override]
    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAttribute(string $name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withAttribute($name, $value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withoutAttribute($name);

        return $new;
    }
}
