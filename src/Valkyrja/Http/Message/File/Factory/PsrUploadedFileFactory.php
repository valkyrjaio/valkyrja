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

namespace Valkyrja\Http\Message\File\Factory;

use Psr\Http\Message\UploadedFileInterface;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Factory\PsrStreamFactory;

use function array_map;

abstract class PsrUploadedFileFactory
{
    /**
     * Get an UploadedFile from a PSR UploadedFileInterface object.
     */
    public static function fromPsr(UploadedFileInterface $file): UploadedFileContract
    {
        return new UploadedFile(
            stream: PsrStreamFactory::fromPsr($file->getStream()),
            uploadError: UploadError::from($file->getError()),
            size: (int) $file->getSize(),
            fileName: $file->getClientFilename(),
            mediaType: $file->getClientMediaType(),
        );
    }

    /**
     * Get an array of UploadedFile objects from an array of PSR UploadedFileInterface objects.
     *
     * @return UploadedFileContract[]
     */
    public static function fromPsrArray(UploadedFileInterface ...$files): array
    {
        return array_map(
            static fn (UploadedFileInterface $file): UploadedFileContract => PsrUploadedFileFactory::fromPsr($file),
            $files,
        );
    }
}
