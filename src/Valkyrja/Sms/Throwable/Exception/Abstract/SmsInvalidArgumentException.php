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

namespace Valkyrja\Sms\Throwable\Exception\Abstract;

use Valkyrja\Sms\Throwable\Contract\SmsThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class SmsInvalidArgumentException extends ValkyrjaInvalidArgumentException implements SmsThrowable
{
}
