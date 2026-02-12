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
use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Psr\UploadedFile as PsrUploadedFile;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Stream\Factory\PsrStreamFactory;

use function is_array;

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
     * @param array<array-key, mixed> $files The files
     */
    public static function fromPsrArray(array $files): UploadedFileCollectionContract
    {
        $collection = [];

        /**
         * @var mixed $file
         */
        foreach ($files as $file) {
            if (is_array($file)) {
                $file = self::fromPsrArray($file);
            }

            if ($file instanceof UploadedFileInterface) {
                $file = self::fromPsr($file);
            }

            if ($file instanceof UploadedFileContract || $file instanceof UploadedFileCollectionContract) {
                $collection[] = $file;
            }
        }

        return UploadedFileCollection::fromArray($collection);
    }

    /**
     * Get an array of PSR UploadedFileInterface objects from an UploadedFileCollection object.
     *
     * @return array<array-key, mixed>
     */
    public static function toPsrArray(UploadedFileCollectionContract $collection): array
    {
        $files = [];

        foreach ($collection->getFiles() as $item) {
            if ($item instanceof UploadedFileCollectionContract) {
                $item = self::toPsrArray($item);
            }

            if ($item instanceof UploadedFileContract) {
                $item = new PsrUploadedFile($item);
            }

            $files[] = $item;
        }

        return $files;
    }
}
