<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
