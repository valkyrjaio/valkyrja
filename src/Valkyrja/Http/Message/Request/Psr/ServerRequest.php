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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Valkyrja\Http\Message\Factory\UploadedFileFactory;
use Valkyrja\Http\Message\File\Psr\UploadedFile;
use Valkyrja\Http\Message\Request\Contract\ServerRequest as ValkyrjaRequest;

/**
 * Class ServerRequest.
 *
 * @author Melech Mizrachi
 *
 * @property ValkyrjaRequest $request
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    public function __construct(ValkyrjaRequest $request)
    {
        parent::__construct($request);
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        return $this->request->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withCookieParams($cookies);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withQueryParams($query);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        $valkyrjaUploadedFiles = $this->request->getUploadedFiles();

        $uploadedFiles = [];

        foreach ($valkyrjaUploadedFiles as $valkyrjaUploadedFile) {
            $uploadedFiles[] = new UploadedFile($valkyrjaUploadedFile);
        }

        return $uploadedFiles;
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $new = clone $this;

        /** @var UploadedFileInterface[] $uploadedFiles */
        $new->request = $this->request->withUploadedFiles(
            UploadedFileFactory::fromPsrArray(...$uploadedFiles)
        );

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody(): object|array|null
    {
        return $this->request->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withParsedBody($data !== null ? (array) $data : []);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withAttribute($name, $value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withoutAttribute($name);

        return $new;
    }
}
