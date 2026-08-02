<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
