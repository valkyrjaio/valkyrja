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
