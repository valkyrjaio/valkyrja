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

namespace Valkyrja\Api\Throwable\Exception\Abstract;

use Valkyrja\Api\Throwable\Contract\ApiThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

abstract class ApiRuntimeException extends ValkyrjaRuntimeException implements ApiThrowable
{
}
