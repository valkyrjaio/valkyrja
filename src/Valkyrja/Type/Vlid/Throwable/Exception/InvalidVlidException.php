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

namespace Valkyrja\Type\Vlid\Throwable\Exception;

use Valkyrja\Type\Uid\Throwable\Exception\InvalidUidException;
use Valkyrja\Type\Vlid\Throwable\Contract\VlidThrowable;

class InvalidVlidException extends InvalidUidException implements VlidThrowable
{
}
