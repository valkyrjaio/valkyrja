<?php

namespace Valkyrja\Tests\Unit\Enum;

use Valkyrja\Enum\Enum;

/**
 * Test implementation of the enum abstract.
 *
 * @author Melech Mizrachi
 */
class EnumClass extends Enum
{
    public const FOO = 'bar';
    public const BAR = 'foo';

    protected const VALUES = [
        self::FOO,
        self::BAR,
    ];
}
