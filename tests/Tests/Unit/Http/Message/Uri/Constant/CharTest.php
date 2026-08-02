<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Constant;

use Valkyrja\Http\Message\Uri\Constant\Char;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function preg_match;

final class CharTest extends TestCase
{
    public function testUnreserved(): void
    {
        self::assertSame('a-zA-Z0-9_\-\.~', Char::UNRESERVED);
    }

    public function testSubDelims(): void
    {
        self::assertSame('!\$&\'\(\)\*\+,;=', Char::SUB_DELIMS);
    }

    public function testUserInfoAddsTheColon(): void
    {
        self::assertSame(Char::UNRESERVED . Char::SUB_DELIMS . ':', Char::USER_INFO);
    }

    public function testHostAddsNothing(): void
    {
        self::assertSame(Char::UNRESERVED . Char::SUB_DELIMS, Char::HOST);
    }

    public function testPathAddsTheColonAtSignAndSlash(): void
    {
        self::assertSame(Char::UNRESERVED . Char::SUB_DELIMS . ':@\/', Char::PATH);
    }

    public function testQueryAddsTheQuestionMarkToThePathSet(): void
    {
        self::assertSame(Char::PATH . '\?', Char::QUERY);
    }

    /**
     * Every component set is a valid character class, so a pattern built from one compiles.
     */
    public function testEverySetIsAValidCharacterClass(): void
    {
        foreach ([Char::USER_INFO, Char::HOST, Char::PATH, Char::QUERY] as $set) {
            self::assertSame(1, preg_match('/[^' . $set . ']/', ' '));
        }
    }
}
