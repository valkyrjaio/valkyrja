<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum AliasClass.
 *
 * @author Melech Mizrachi
 */
final class AliasClass extends Enum
{
    public const REQUEST_METHOD = \Valkyrja\Http\Enums\RequestMethod::class;
    public const STATUS_CODE    = \Valkyrja\Http\Enums\StatusCode::class;
}
