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

namespace Valkyrja\Http\Message\File\Psr;

use Override;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\Stream\Psr\Stream;

class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        protected UploadedFileContract $file,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getStream(): StreamInterface
    {
        $stream = $this->file->getStream();

        return new Stream($stream);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function moveTo(string $targetPath): void
    {
        $this->file->moveTo($targetPath);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSize(): int|null
    {
        return $this->file->getSize();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getError(): int
    {
        return $this->file->getError()->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getClientFilename(): string|null
    {
        return $this->file->getClientFilename();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getClientMediaType(): string|null
    {
        return $this->file->getClientMediaType();
    }
}
