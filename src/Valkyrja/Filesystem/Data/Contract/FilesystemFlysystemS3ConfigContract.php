<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
