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

use Valkyrja\Type\Enum\Contract\EnumContract;
use Valkyrja\Type\Enum\Trait\Enumerable;

/**
 * Model class to use to test string BackedEnum.
 */
enum StringEnum: string implements EnumContract
{
    use Enumerable;

    case foo = 'bar';
}
