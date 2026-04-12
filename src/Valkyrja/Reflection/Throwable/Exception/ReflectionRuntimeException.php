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

namespace Valkyrja\Reflection\Throwable\Exception;

use Valkyrja\Reflection\Throwable\Contract\ReflectionThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

class ReflectionRuntimeException extends ValkyrjaRuntimeException implements ReflectionThrowable
{
}
