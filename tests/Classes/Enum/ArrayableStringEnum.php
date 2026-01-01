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

use Valkyrja\Type\BuiltIn\Enum\Contract\ArrayableContract;
use Valkyrja\Type\BuiltIn\Enum\Contract\JsonSerializableContract;
use Valkyrja\Type\BuiltIn\Enum\Trait\Arrayable;
use Valkyrja\Type\BuiltIn\Enum\Trait\JsonSerializable;

/**
 * Enum class to use to test Arrayable String Backed Enum.
 */
enum ArrayableStringEnum: string implements ArrayableContract, JsonSerializableContract
{
    use Arrayable;
    use JsonSerializable;

    case foo   = 'bar';
    case lorem = 'ipsum';
}
