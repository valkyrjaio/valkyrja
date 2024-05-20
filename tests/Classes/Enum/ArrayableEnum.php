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

use Valkyrja\Type\BuiltIn\Enum\Arrayable;
use Valkyrja\Type\BuiltIn\Enum\Contract\Arrayable as ArrayableContract;
use Valkyrja\Type\BuiltIn\Enum\Contract\JsonSerializable as JsonSerializableContract;
use Valkyrja\Type\BuiltIn\Enum\JsonSerializable;

/**
 * Enum class to use to test Arrayable Enum.
 *
 * @author Melech Mizrachi
 */
enum ArrayableEnum implements ArrayableContract, JsonSerializableContract
{
    use Arrayable;
    use JsonSerializable;

    case spade;
    case heart;
    case diamond;
    case club;
}
