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

namespace Valkyrja\View\Throwable\Exception;

use Valkyrja\Throwable\Exception\InvalidArgumentException as ThrowableInvalidArgumentException;
use Valkyrja\View\Throwable\Contract\Throwable;

/**
 * Class InvalidArgumentException.
 *
 * @author Melech Mizrachi
 */
class InvalidArgumentException extends ThrowableInvalidArgumentException implements Throwable
{
}
