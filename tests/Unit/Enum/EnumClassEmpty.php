<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
