<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Crypt\Data;

use Valkyrja\Crypt\Data\Contract\CryptConfigContract;
use Valkyrja\Crypt\Data\CryptConfig;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Crypt\Manager\SodiumCrypt;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CryptConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CryptConfigContract::class, new CryptConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(SodiumCrypt::class, new CryptConfig()->defaultCrypt);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(NullCrypt::class, new CryptConfig(defaultCrypt: NullCrypt::class)->defaultCrypt);
    }
}
