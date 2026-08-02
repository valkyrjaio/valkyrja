<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Vlid\Factory\Abstract;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Vlid\Enum\Version;

abstract class VlidTestCase extends TestCase
{
    protected function ensureVersionInGeneratedString(Version $version, string $generated): void
    {
        self::assertSame((string) $version->value, $generated[13]);
    }
}
