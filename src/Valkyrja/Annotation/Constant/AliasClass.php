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

namespace Valkyrja\Annotation\Constant;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;

/**
 * Constant AliasClass.
 *
 * @author Melech Mizrachi
 */
final class AliasClass
{
    public const string REQUEST_METHOD = RequestMethod::class;
    public const string STATUS_CODE    = StatusCode::class;
}
