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

namespace Valkyrja\Validation\Throwable\Exception\Abstract;

use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;
use Valkyrja\Validation\Throwable\Contract\ValidationThrowable;

abstract class ValidationRuntimeException extends ValkyrjaRuntimeException implements ValidationThrowable
{
}
