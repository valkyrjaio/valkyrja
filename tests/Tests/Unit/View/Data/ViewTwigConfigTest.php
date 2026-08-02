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
use Valkyrja\View\Data\Contract\ViewTwigConfigContract;
use Valkyrja\View\Data\ViewTwigConfig;

final class ViewTwigConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(ViewTwigConfigContract::class, new ViewTwigConfig());
    }

    public function testDefaults(): void
    {
        $config = new ViewTwigConfig();

        self::assertSame([], $config->twigPaths);
        self::assertSame([], $config->twigExtensions);
        self::assertSame('/storage/views', $config->twigCompiledPath);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new ViewTwigConfig(
            twigPaths: ['test' => '/test'],
            twigExtensions: [],
            twigCompiledPath: '/storage',
        );

        self::assertSame(['test' => '/test'], $config->twigPaths);
        self::assertSame([], $config->twigExtensions);
        self::assertSame('/storage', $config->twigCompiledPath);
    }
}
