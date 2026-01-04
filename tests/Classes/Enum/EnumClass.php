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

use Valkyrja\Type\BuiltIn\Enum\Contract\EnumContract;
use Valkyrja\Type\BuiltIn\Enum\Trait\Enumerable;

/**
 * Enum class to use to test enums.
 */
enum EnumClass implements EnumContract
{
    use Enumerable;

    case spade;
    case heart;
    case diamond;
    case club;
}
