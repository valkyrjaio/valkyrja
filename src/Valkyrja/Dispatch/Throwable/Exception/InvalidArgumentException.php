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

namespace Valkyrja\Dispatch\Throwable\Exception;

use Valkyrja\Dispatch\Throwable\Contract\Throwable;
use Valkyrja\Throwable\Exception\InvalidArgumentException as ThrowableInvalidArgumentException;

/**
 * Class InvalidArgumentException.
 */
class InvalidArgumentException extends ThrowableInvalidArgumentException implements Throwable
{
}
