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

namespace Valkyrja\Tests\Classes\Http\Struct;

use Valkyrja\Http\Struct\Response\Contract\ResponseStruct as Contract;
use Valkyrja\Http\Struct\Response\ResponseStruct;

/**
 * Struct TestResponseStruct.
 *
 * @author Melech Mizrachi
 */
enum TestIndexedResponseStruct: int implements Contract
{
    use ResponseStruct;

    case first  = 1;
    case second = 2;
    case third  = 3;
}
