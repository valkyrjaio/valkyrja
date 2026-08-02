<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Stream\Enum;

use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ModeTest extends TestCase
{
    public function testIsReadable(): void
    {
        self::assertTrue(Mode::READ->isReadable());
        self::assertTrue(Mode::READ_WRITE->isReadable());
        self::assertTrue(Mode::WRITE_READ->isReadable());
        self::assertTrue(Mode::WRITE_READ_END->isReadable());
        self::assertTrue(Mode::CREATE_WRITE_READ->isReadable());
        self::assertTrue(Mode::WRITE_READ_CREATE->isReadable());

        self::assertFalse(Mode::WRITE->isReadable());
        self::assertFalse(Mode::WRITE_END->isReadable());
        self::assertFalse(Mode::CREATE_WRITE->isReadable());
        self::assertFalse(Mode::WRITE_CREATE->isReadable());
        self::assertFalse(Mode::CLOSE_ON_EXEC->isReadable());
    }

    public function testIsWritable(): void
    {
        self::assertTrue(Mode::READ_WRITE->isWriteable());
        self::assertTrue(Mode::WRITE->isWriteable());
        self::assertTrue(Mode::WRITE_READ->isWriteable());
        self::assertTrue(Mode::WRITE_END->isWriteable());
        self::assertTrue(Mode::WRITE_READ_END->isWriteable());
        self::assertTrue(Mode::CREATE_WRITE->isWriteable());
        self::assertTrue(Mode::CREATE_WRITE_READ->isWriteable());
        self::assertTrue(Mode::WRITE_CREATE->isWriteable());
        self::assertTrue(Mode::WRITE_READ_CREATE->isWriteable());

        self::assertFalse(Mode::READ->isWriteable());
        self::assertFalse(Mode::CLOSE_ON_EXEC->isWriteable());
    }
}
