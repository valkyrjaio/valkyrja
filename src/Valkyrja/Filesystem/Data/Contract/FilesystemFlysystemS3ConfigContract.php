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

namespace Valkyrja\Filesystem\Data\Contract;

interface FilesystemFlysystemS3ConfigContract
{
    /** @var non-empty-string */
    public string $flysystemS3Key {
        get;
    }

    /** @var non-empty-string */
    public string $flysystemS3Secret {
        get;
    }

    /** @var non-empty-string */
    public string $flysystemS3Region {
        get;
    }

    /** @var non-empty-string */
    public string $flysystemS3Version {
        get;
    }

    /** @var non-empty-string */
    public string $flysystemS3Bucket {
        get;
    }

    public string $flysystemS3Prefix {
        get;
    }

    /** @var array<array-key, mixed> */
    public array $flysystemS3Options {
        get;
    }
}
