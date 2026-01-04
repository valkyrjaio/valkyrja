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

namespace Valkyrja\Tests\Classes\Enum;

use Valkyrja\Type\BuiltIn\Enum\Contract\EnumContract as Contract;
use Valkyrja\Type\BuiltIn\Enum\Trait\Enumerable;

/**
 * Model class to use to test int BackedEnum.
 */
enum IntEnum: int implements Contract
{
    use Enumerable;

    case first  = 1;
    case second = 2;
}
