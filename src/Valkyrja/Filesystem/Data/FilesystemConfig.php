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

namespace Valkyrja\Filesystem\Data;

use Valkyrja\Filesystem\Data\Contract\FilesystemConfigContract;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;

class FilesystemConfig implements FilesystemConfigContract
{
    /**
     * @param class-string<FilesystemContract> $defaultFilesystem The filesystem to use by default
     */
    public function __construct(
        public readonly string $defaultFilesystem = FlysystemFilesystem::class,
    ) {
    }
}
