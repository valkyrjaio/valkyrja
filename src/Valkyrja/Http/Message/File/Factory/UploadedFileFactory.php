<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\File\Factory;

use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidFilesArrayStructureException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidTmpNameException;
use Valkyrja\Http\Message\File\Throwable\Exception\UploadedFileInvalidValueException;
use Valkyrja\Http\Message\File\UploadedFile;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;

use function array_keys;
use function is_array;
use function is_string;
use function str_starts_with;

use const PHP_SAPI;

abstract class UploadedFileFactory
{
    /**
     * Normalize uploaded files.
     * Transforms each value into an UploadedFile instance, and ensures
     * that nested arrays are normalized.
     *
     * @param array<array-key, mixed> $files The files
     *
     * @throws HttpMessageInvalidArgumentException for unrecognized values
     */
    public static function normalizeFiles(array $files): UploadedFileCollectionContract
    {
        $normalized = [];

        /**
         * @var array-key                                         $key
         * @var array<array-key, mixed>|UploadedFileContract|null $value
         */
        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFileContract) {
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

            throw new UploadedFileInvalidValueException('Invalid value in files specification');
        }

        return new UploadedFileCollection($normalized);
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array<array-key, mixed> $value $_FILES struct
     *
     * @throws UploadedFileInvalidFilesArrayStructureException
     */
    public static function createUploadedFileFromSpec(array $value): UploadedFileContract|UploadedFileCollectionContract
    {
        /** @var array<array-key, mixed>|string|null $tmpName */
        $tmpName = $value['tmp_name'] ?? null;

        if (is_array($tmpName)) {
            return self::normalizeNestedFileSpec($value);
        }

        if (! is_string($tmpName)) {
            throw new UploadedFileInvalidTmpNameException('Temp file name expected to be a string');
        }

        /**
         * @var array{
         *     tmp_name: string,
         *     size: int|string,
         *     error: int|string,
         *     name: string|int,
         *     type: string|int,
         * } $value
         */

        return new UploadedFile(
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
     * @throws UploadedFileInvalidFilesArrayStructureException
     */
    public static function normalizeNestedFileSpec(array $files = []): UploadedFileCollectionContract
    {
        $normalizedFiles = [];
        /** @var array<array-key, mixed>|null $filesTmpName */
        $filesTmpName = $files['tmp_name'] ?? null;

        if (! is_array($filesTmpName)) {
            throw new UploadedFileInvalidFilesArrayStructureException('Expecting tmp name to be a nested array of files');
        }

        foreach (array_keys($filesTmpName) as $key) {
            /**
             * @var array{
             *     tmp_name: array<array-key, string|array<array-key, mixed>|null>,
             *     size: array<array-key, int|null>,
             *     error: array<array-key, int|null>,
             *     name: array<array-key, string|null>,
             *     type: array<array-key, string|null>
             * }                                   $files
             */
            $tmpName = $files['tmp_name'][$key] ?? '';
            $size    = $files['size'][$key] ?? 0;
            $error   = $files['error'][$key] ?? 0;
            $name    = $files['name'][$key] ?? null;
            $type    = $files['type'][$key] ?? null;

            $normalizedFiles[$key] = self::createUploadedFileFromSpec([
                'tmp_name' => $tmpName,
                'size'     => $size,
                'error'    => $error,
                'name'     => $name,
                'type'     => $type,
            ]);
        }

        return new UploadedFileCollection($normalizedFiles);
    }

    /**
     * Determine if the current environment is suitable for uploading files.
     */
    public static function isValidSapiEnvironmentForUploads(): bool
    {
        $sapi = static::getSapi();

        // If the PHP_SAPI value is not set to a CLI environment
        // and not a PHP debugger environment
        return ! str_starts_with($sapi, 'cli')
            && ! str_starts_with($sapi, 'phpdbg');
    }

    /**
     * Get the SAPI name the current process is running under.
     *
     * A seam: PHP_SAPI is fixed for the lifetime of the process, so a test can
     * only reach the non-CLI arms of isValidSapiEnvironmentForUploads() by
     * overriding this.
     */
    protected static function getSapi(): string
    {
        return PHP_SAPI;
    }
}
