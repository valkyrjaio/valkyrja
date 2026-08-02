<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Struct;

use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;
use Valkyrja\Http\Struct\Response\Trait\ResponseStruct;

/**
 * Struct TestResponseStruct.
 */
enum IndexedResponseStructEnum: int implements ResponseStructContract
{
    use ResponseStruct;

    case first  = 1;
    case second = 2;
    case third  = 3;
}
