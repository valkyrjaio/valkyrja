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

namespace Valkyrja\Throwable\Contract;

use Throwable;

interface ValkyrjaThrowable extends Throwable
{
    /**
     * Get trace code unique to the exception being thrown.
     *
     * @returns non-empty-string
     */
    public function getTraceCode(): string;
}
