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

namespace Valkyrja\Container\Throwable\Exception;

use Valkyrja\Container\Throwable\Contract\ContainerThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

class ContainerRuntimeException extends ValkyrjaRuntimeException implements ContainerThrowable
{
}
