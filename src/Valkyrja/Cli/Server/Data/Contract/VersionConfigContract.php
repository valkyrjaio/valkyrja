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

namespace Valkyrja\Cli\Server\Data\Contract;

interface VersionConfigContract
{
    /** @var non-empty-string */
    public string $versionCommandName {
        get;
    }

    /** @var non-empty-string */
    public string $versionOptionName {
        get;
    }

    /** @var non-empty-string */
    public string $versionOptionShortName {
        get;
    }
}
