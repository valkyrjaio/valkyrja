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

use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Http\Struct\Request\QueryRequestStruct;

/**
 * Struct TestWithNoRulesQueryRequestStruct.
 *
 * @author Melech Mizrachi
 */
enum WithNoRulesQueryRequestStructEnum implements RequestStruct
{
    use QueryRequestStruct;

    case first;
    case second;
    case third;
}
