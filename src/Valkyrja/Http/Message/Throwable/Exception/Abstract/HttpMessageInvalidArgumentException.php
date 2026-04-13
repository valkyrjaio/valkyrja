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

namespace Valkyrja\Http\Message\Throwable\Exception\Abstract;

use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpInvalidArgumentException;

abstract class HttpMessageInvalidArgumentException extends HttpInvalidArgumentException implements HttpMessageThrowable
{
}
