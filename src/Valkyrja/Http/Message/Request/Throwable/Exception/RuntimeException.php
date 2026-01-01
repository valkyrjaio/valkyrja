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

namespace Valkyrja\Http\Message\Request\Throwable\Exception;

use Valkyrja\Http\Message\Request\Throwable\Contract\Throwable;
use Valkyrja\Http\Message\Throwable\Exception\RuntimeException as ParentException;

/**
 * Class RuntimeException.
 */
class RuntimeException extends ParentException implements Throwable
{
}
