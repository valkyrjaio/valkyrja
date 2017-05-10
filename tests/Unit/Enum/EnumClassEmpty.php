<?php

namespace Valkyrja\Tests\Unit\Enum;

use Valkyrja\Enum\Enum;

/**
 * Test implementation of the enum abstract with no default values array set.
 *
 * @author Melech Mizrachi
 */
class EnumClassEmpty extends Enum
{
    public const FOO = 'bar';
    public const BAR = 'foo';
}
