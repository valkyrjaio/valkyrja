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

namespace Valkyrja\Cache\Throwable\Exception\Abstract;

use Valkyrja\Cache\Throwable\Contract\CacheThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class CacheInvalidArgumentException extends ValkyrjaInvalidArgumentException implements CacheThrowable
{
}
