<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Message\File\Trait;

use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;

/**
 * Narrowing helpers for an uploaded file collection's mixed values.
 */
trait UploadedFileCollectionTrait
{
    /**
     * Get a nested collection, failing the test when the key holds a file.
     *
     * @param UploadedFileCollectionContract<UploadedFileCollectionContract|UploadedFileContract> $collection The collection
     * @param non-empty-string|int                                                                $key        The key
     */
    protected static function getNestedCollection(
        UploadedFileCollectionContract $collection,
        string|int $key
    ): UploadedFileCollectionContract {
        $nested = $collection->get($key);

        if (! $nested instanceof UploadedFileCollectionContract) {
            self::fail("Expected a nested uploaded file collection at $key.");
        }

        return $nested;
    }

    /**
     * Get a file, failing the test when the key holds a nested collection.
     *
     * @param UploadedFileCollectionContract<UploadedFileCollectionContract|UploadedFileContract> $collection The collection
     * @param non-empty-string|int                                                                $key        The key
     */
    protected static function getFile(
        UploadedFileCollectionContract $collection,
        string|int $key
    ): UploadedFileContract {
        $file = $collection->get($key);

        if (! $file instanceof UploadedFileContract) {
            self::fail("Expected an uploaded file at $key.");
        }

        return $file;
    }
}
