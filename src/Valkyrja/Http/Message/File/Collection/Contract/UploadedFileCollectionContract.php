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

namespace Valkyrja\Http\Message\File\Collection\Contract;

use Valkyrja\Http\Message\File\Contract\UploadedFileContract;

/**
 * @template T of UploadedFileContract|self
 */
interface UploadedFileCollectionContract
{
    /**
     * Determine if a file exists.
     *
     * @param non-empty-string|int $name The file name
     */
    public function hasFile(string|int $name): bool;

    /**
     * Get a file.
     *
     * @param non-empty-string|int $name The file name
     *
     * @return T|null
     */
    public function getFile(string|int $name): UploadedFileContract|self|null;

    /**
     * Get all the files.
     *
     * @return array<array-key, T>
     */
    public function getFiles(): array;

    /**
     * Get only the specified files.
     *
     * @param non-empty-string|int ...$names The file names
     *
     * @return array<array-key, T>
     */
    public function onlyFiles(string|int ...$names): array;

    /**
     * Get all the files except the specified ones.
     *
     * @param non-empty-string|int ...$names The file names
     *
     * @return array<array-key, T>
     */
    public function exceptFiles(string|int ...$names): array;

    /**
     * Get a new instance with the specified files.
     *
     * @param array<array-key, T> $files The files
     */
    public function withFiles(array $files): static;

    /**
     * Get a new instance with the added files.
     */
    public function withAddedFiles(UploadedFileContract|self ...$files): static;
}
