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

namespace Valkyrja\View\Data\Contract;

interface ViewPhpConfigContract
{
    /** @var non-empty-string */
    public string $phpPath {
        get;
    }

    /** @var non-empty-string */
    public string $phpFileExtension {
        get;
    }

    /** @var array<string, string> */
    public array $phpPaths {
        get;
    }
}
