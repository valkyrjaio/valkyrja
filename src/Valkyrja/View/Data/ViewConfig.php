<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Data;

use Valkyrja\View\Data\Contract\ViewConfigContract;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\PhpRenderer;

class ViewConfig implements ViewConfigContract
{
    /**
     * @param class-string<RendererContract> $defaultRenderer The renderer to use by default
     */
    public function __construct(
        public readonly string $defaultRenderer = PhpRenderer::class,
    ) {
    }
}
