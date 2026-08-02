<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Filesystem\Data;

use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemConfigContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Filesystem\Manager\LocalFlysystemFilesystem;

class FilesystemFlysystemConfig implements FilesystemFlysystemConfigContract
{
    /**
     * @param class-string<FlysystemFilesystem> $defaultFlysystemFilesystem The flysystem filesystem to use by default
     */
    public function __construct(
        public readonly string $defaultFlysystemFilesystem = LocalFlysystemFilesystem::class,
    ) {
    }
}
