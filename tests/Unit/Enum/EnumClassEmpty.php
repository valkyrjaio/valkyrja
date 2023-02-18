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

namespace Valkyrja\Tests\Unit\Enum;

use Valkyrja\Type\Types\Enum;

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
