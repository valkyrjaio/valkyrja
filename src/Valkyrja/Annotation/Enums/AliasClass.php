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

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Support\Enum\Enum;

/**
 * Enum AliasClass.
 *
 * @author Melech Mizrachi
 */
final class AliasClass extends Enum
{
    public const REQUEST_METHOD = RequestMethod::class;
    public const STATUS_CODE    = StatusCode::class;
}
