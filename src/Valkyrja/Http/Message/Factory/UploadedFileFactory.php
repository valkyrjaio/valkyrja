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

namespace Valkyrja\Http\Message\Factory;

use Psr\Http\Message\UploadedFileInterface;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\File\Contract\UploadedFile;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\UploadedFile as HttpUploadedFile;

use function array_keys;
use function array_map;
use function is_array;

/**
 * Abstract Class UploadedFileFactory.
 *
 * @author Melech Mizrachi
 */
abstract class UploadedFileFactory
{
    /**
     * Normalize uploaded files.
     * Transforms each value into an UploadedFile instance, and ensures
     * that nested arrays are normalized.
     *
     * @param array<array-key, mixed> $files The files
     *
     * @throws InvalidArgumentException for unrecognized values
     *
     * @return array<array-key, mixed>
     */
    public static function normalizeFiles(array $files): array
    {
        $normalized = [];

        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFile) {
                $normalized[$key] = $value;

                continue;
            }

            if (is_array($value) && isset($value['tmp_name'])) {
                $normalized[$key] = self::createUploadedFileFromSpec($value);

                continue;
            }

            if (is_array($value)) {
                $normalized[$key] = self::normalizeFiles($value);

                continue;
            }

            throw new InvalidArgumentException('Invalid value in files specification');
        }

        return $normalized;
    }

    public static function fromPsr(UploadedFileInterface $file): UploadedFile
    {
        return new HttpUploadedFile(
            stream: StreamFactory::fromPsr($file->getStream()),
            uploadError: UploadError::from($file->getError()),
            size: (int) $file->getSize(),
            fileName: $file->getClientFilename(),
            mediaType: $file->getClientMediaType(),
        );
    }

    /**
     * @param UploadedFileInterface ...$files
     *
     * @return UploadedFile[]
     */
    public static function fromPsrArray(UploadedFileInterface ...$files): array
    {
        return array_map(
            static fn (UploadedFileInterface $file): UploadedFile => UploadedFileFactory::fromPsr($file),
            $files,
        );
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array<array-key, mixed> $value $_FILES struct
     *
     * @throws InvalidArgumentException
     *
     * @return UploadedFile|UploadedFile[]
     */
    private static function createUploadedFileFromSpec(array $value): UploadedFile|array
    {
        if (is_array($value['tmp_name'])) {
            return self::normalizeNestedFileSpec($value);
        }

        return new HttpUploadedFile(
            $value['tmp_name'],
            null,
            UploadError::from($value['error']),
            $value['size'],
            $value['name'],
            $value['type']
        );
    }

    /**
     * Normalize an array of file specifications.
     * Loops through all nested files and returns a normalized array of
     * UploadedFileInterface instances.
     *
     * @param array<array-key, mixed> $files
     *
     * @throws InvalidArgumentException
     *
     * @return UploadedFile[]
     */
    private static function normalizeNestedFileSpec(array $files = []): array
    {
        $normalizedFiles = [];

        foreach (array_keys($files['tmp_name']) as $key) {
            $spec                  = [
                'tmp_name' => $files['tmp_name'][$key],
                'size'     => $files['size'][$key] ?? 0,
                'error'    => $files['error'][$key] ?? 0,
                'name'     => $files['name'][$key] ?? null,
                'type'     => $files['type'][$key] ?? null,
            ];
            $normalizedFiles[$key] = self::createUploadedFileFromSpec($spec);
        }

        return $normalizedFiles;
    }
}
