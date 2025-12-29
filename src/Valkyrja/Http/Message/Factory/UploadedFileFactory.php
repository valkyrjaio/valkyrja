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
use Valkyrja\Http\Message\File\Contract\UploadedFile;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\UploadedFile as HttpUploadedFile;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;

use function array_keys;
use function array_map;
use function is_array;
use function is_string;

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

        /**
         * @var array-key $key
         * @var mixed     $value
         */
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
    public static function createUploadedFileFromSpec(array $value): UploadedFile|array
    {
        $tmpName = $value['tmp_name'] ?? null;

        if (is_array($tmpName)) {
            return self::normalizeNestedFileSpec($value);
        }

        if (! is_string($tmpName)) {
            throw new InvalidArgumentException('Temp file name expected to be a string');
        }

        return new HttpUploadedFile(
            $tmpName,
            null,
            UploadError::from((int) $value['error']),
            (int) $value['size'],
            (string) $value['name'],
            (string) $value['type']
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
     *
     * @psalm-suppress InvalidReturnType Cannot do recursive return type
     * @psalm-suppress InvalidReturnStatement Cannot do recursive return type
     * @psalm-suppress MixedArrayAccess tmp_name should exist
     */
    public static function normalizeNestedFileSpec(array $files = []): array
    {
        $normalizedFiles = [];
        $filesTmpName    = $files['tmp_name'] ?? null;

        if (! is_array($filesTmpName)) {
            throw new InvalidArgumentException('Expecting tmp name to be a nested array of files');
        }

        foreach (array_keys($filesTmpName) as $key) {
            $spec                  = [
                'tmp_name' => $files['tmp_name'][$key] ?? '',
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
