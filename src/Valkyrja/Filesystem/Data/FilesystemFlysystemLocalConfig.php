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

use Valkyrja\Filesystem\Data\Contract\FilesystemFlysystemLocalConfigContract;

class FilesystemFlysystemLocalConfig implements FilesystemFlysystemLocalConfigContract
{
    /**
     * @param non-empty-string $flysystemLocalPath The path to store files under
     */
    public function __construct(
        public readonly string $flysystemLocalPath = '/storage/app',
    ) {
    }
}
