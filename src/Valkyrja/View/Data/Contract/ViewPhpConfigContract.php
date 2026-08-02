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
