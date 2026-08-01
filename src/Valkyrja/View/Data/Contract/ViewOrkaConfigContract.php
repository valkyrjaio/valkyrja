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

use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

interface ViewOrkaConfigContract
{
    /** @var non-empty-string */
    public string $orkaPath {
        get;
    }

    /** @var non-empty-string */
    public string $orkaFileExtension {
        get;
    }

    /** @var array<non-empty-string, non-empty-string> */
    public array $orkaPaths {
        get;
    }

    /** @var class-string<ReplacementContract>[] */
    public array $orkaCoreReplacements {
        get;
    }

    /** @var class-string<ReplacementContract>[] */
    public array $orkaReplacements {
        get;
    }
}
