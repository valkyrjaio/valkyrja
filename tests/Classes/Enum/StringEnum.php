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
use Valkyrja\Type\BuiltIn\Enum\Trait\Enum;

/**
 * Model class to use to test string BackedEnum.
 *
 * @author Melech Mizrachi
 */
enum StringEnum: string implements Contract
{
    use Enum;

    case foo = 'bar';
}
