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

namespace Valkyrja\Tests\Unit\View\Data;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Data\Contract\ViewConfigContract;
use Valkyrja\View\Data\ViewConfig;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;

final class ViewConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(ViewConfigContract::class, new ViewConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(PhpRenderer::class, new ViewConfig()->defaultRenderer);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            OrkaRenderer::class,
            new ViewConfig(defaultRenderer: OrkaRenderer::class)->defaultRenderer
        );
    }
}
