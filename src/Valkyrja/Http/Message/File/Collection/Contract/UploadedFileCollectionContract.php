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
 * @template T of UploadedFileContract|UploadedFileCollectionContract
 */
interface UploadedFileCollectionContract
{
    /**
     * Determine if a file item exists.
     *
     * @param non-empty-string|int $key The key
     */
    public function has(string|int $key): bool;

    /**
     * Get a file item.
     *
     * @param non-empty-string|int $key The key
     *
     * @return T|null
     */
    public function get(string|int $key): UploadedFileContract|self|null;

    /**
     * Get all the files.
     *
     * @return array<array-key, T>
     */
    public function getAll(): array;

    /**
     * Get only the specified files.
     *
     * @param non-empty-string|int ...$keys The keys
     *
     * @return array<array-key, T>
     */
    public function getOnly(string|int ...$keys): array;

    /**
     * Get all the files except the specified ones.
     *
     * @param non-empty-string|int ...$keys The keys
     *
     * @return array<array-key, T>
     */
    public function getAllExcept(string|int ...$keys): array;

    /**
     * Get a new instance with the specified collection of files.
     *
     * @param array<array-key, T> $collection The collection
     */
    public function with(array $collection): static;

    /**
     * Get a new instance with the added collection of files.
     *
     * @param array<array-key, T> $collection The collection
     */
    public function withAdded(array $collection): static;
}
