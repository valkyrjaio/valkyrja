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

use Valkyrja\Type\BuiltIn\Enum\Contract\Enum as Contract;
use Valkyrja\Type\BuiltIn\Enum\Enum as EnumTrait;

/**
 * Enum class to use to test enums.
 *
 * @author Melech Mizrachi
 */
enum Enum implements Contract
{
    use EnumTrait;

    case spade;
    case heart;
    case diamond;
    case club;
}
