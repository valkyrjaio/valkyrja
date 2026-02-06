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

namespace Valkyrja\Type\Object\Throwable\Exception;

use Valkyrja\Type\Object\Throwable\Contract\ClassThrowable;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;

class InvalidClassPropertyProvidedException extends InvalidArgumentException implements ClassThrowable
{
}
