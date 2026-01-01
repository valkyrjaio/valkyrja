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

namespace Valkyrja\Session\Throwable\Exception;

use Valkyrja\Session\Throwable\Contract\Throwable;
use Valkyrja\Throwable\Exception\RuntimeException as ThrowableRuntimeException;

class RuntimeException extends ThrowableRuntimeException implements Throwable
{
}
