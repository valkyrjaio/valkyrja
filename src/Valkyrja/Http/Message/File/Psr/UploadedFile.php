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

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Valkyrja\Http\Message\File\Contract\UploadedFile as ValkyrjaUploadedFile;
use Valkyrja\Http\Message\Stream\Psr\Stream;

/**
 * Class UploadedFile.
 *
 * @author Melech Mizrachi
 */
class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        protected ValkyrjaUploadedFile $file,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getStream(): StreamInterface
    {
        $stream = $this->file->getStream();

        return new Stream($stream);
    }

    /**
     * @inheritDoc
     */
    public function moveTo(string $targetPath): void
    {
        $this->file->moveTo($targetPath);
    }

    /**
     * @inheritDoc
     */
    public function getSize(): int|null
    {
        return $this->file->getSize();
    }

    /**
     * @inheritDoc
     */
    public function getError(): int
    {
        return $this->file->getError()->value;
    }

    /**
     * @inheritDoc
     */
    public function getClientFilename(): string|null
    {
        return $this->file->getClientFilename();
    }

    /**
     * @inheritDoc
     */
    public function getClientMediaType(): string|null
    {
        return $this->file->getClientMediaType();
    }
}
