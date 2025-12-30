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

namespace Valkyrja\Http\Middleware\Throwable\Exception;

use Valkyrja\Http\Middleware\Throwable\Contract\Throwable;
use Valkyrja\Http\Throwable\Exception\RuntimeException as HttpRuntimeException;

/**
 * Class RuntimeException.
 *
 * @author Melech Mizrachi
 */
class RuntimeException extends HttpRuntimeException implements Throwable
{
}
