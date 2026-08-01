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
