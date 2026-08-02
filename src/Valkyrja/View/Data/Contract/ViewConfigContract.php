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

use Valkyrja\View\Renderer\Contract\RendererContract;

interface ViewConfigContract
{
    /** @var class-string<RendererContract> */
    public string $defaultRenderer {
        get;
    }
}
