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

namespace Valkyrja\Throwable\Factory;

use Throwable;

use function md5;

class ThrowableFactory
{
    /**
     * Get the trace code for a throwable.
     *
     * @param Throwable $throwable The throwable
     */
    public static function getTraceCode(Throwable $throwable): string
    {
        return md5($throwable::class . $throwable->getTraceAsString());
    }
}
