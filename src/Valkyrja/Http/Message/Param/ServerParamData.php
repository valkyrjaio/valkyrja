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

namespace Valkyrja\Http\Message\Param;

use Valkyrja\Http\Message\Param\Abstract\ParamData;
use Valkyrja\Http\Message\Param\Contract\ServerParamDataContract;

/**
 * @extends ParamData<scalar|self>
 *
 * @phpstan-ignore-next-line
 */
class ServerParamData extends ParamData implements ServerParamDataContract
{
}
