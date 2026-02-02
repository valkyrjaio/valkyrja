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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Format;

use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;
use Valkyrja\Cli\Interaction\Format\Format;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Format class.
 */
class FormatTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $format = new Format('1', '22');

        self::assertInstanceOf(FormatContract::class, $format);
    }

    public function testSetState(): void
    {
        $setCode   = '1';
        $unsetCode = '22';

        $format  = Format::__set_state([
            'setCode'   => $setCode,
            'unsetCode' => $unsetCode,
        ]);

        self::assertSame($setCode, $format->getSetCode());
        self::assertSame($unsetCode, $format->getUnsetCode());
    }

    public function testGetSetCode(): void
    {
        $setCode = '1';
        $format  = new Format($setCode, '22');

        self::assertSame($setCode, $format->getSetCode());
    }

    public function testGetUnsetCode(): void
    {
        $unsetCode = '22';
        $format    = new Format('1', $unsetCode);

        self::assertSame($unsetCode, $format->getUnsetCode());
    }

    public function testWithSetCode(): void
    {
        $format     = new Format('1', '22');
        $newSetCode = '4';
        $newFormat  = $format->withSetCode($newSetCode);

        self::assertNotSame($format, $newFormat);
        self::assertSame('1', $format->getSetCode());
        self::assertSame($newSetCode, $newFormat->getSetCode());
        self::assertSame('22', $newFormat->getUnsetCode());
    }

    public function testWithUnsetCode(): void
    {
        $format       = new Format('1', '22');
        $newUnsetCode = '24';
        $newFormat    = $format->withUnsetCode($newUnsetCode);

        self::assertNotSame($format, $newFormat);
        self::assertSame('22', $format->getUnsetCode());
        self::assertSame($newUnsetCode, $newFormat->getUnsetCode());
        self::assertSame('1', $newFormat->getSetCode());
    }
}
