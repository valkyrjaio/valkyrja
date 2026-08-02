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
use Valkyrja\View\Data\Contract\ViewOrkaConfigContract;
use Valkyrja\View\Data\ViewOrkaConfig;
use Valkyrja\View\Orka\Constant\OrkaReplacementCollection;

final class ViewOrkaConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(ViewOrkaConfigContract::class, new ViewOrkaConfig());
    }

    public function testDefaults(): void
    {
        $config = new ViewOrkaConfig();

        self::assertSame('/resources/views', $config->orkaPath);
        self::assertSame('.orka.phtml', $config->orkaFileExtension);
        self::assertSame([], $config->orkaPaths);
        self::assertSame(OrkaReplacementCollection::CORE, $config->orkaCoreReplacements);
        self::assertSame(OrkaReplacementCollection::DEBUG, $config->orkaReplacements);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new ViewOrkaConfig(
            orkaPath: '/storage',
            orkaFileExtension: '.test.orka.phtml',
            orkaPaths: ['test' => '/test'],
            orkaCoreReplacements: [],
            orkaReplacements: [],
        );

        self::assertSame('/storage', $config->orkaPath);
        self::assertSame('.test.orka.phtml', $config->orkaFileExtension);
        self::assertSame(['test' => '/test'], $config->orkaPaths);
        self::assertSame([], $config->orkaCoreReplacements);
        self::assertSame([], $config->orkaReplacements);
    }
}
