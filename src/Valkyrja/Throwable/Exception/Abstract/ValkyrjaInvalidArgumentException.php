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

namespace Valkyrja\Throwable\Exception\Abstract;

use InvalidArgumentException;
use Override;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Factory\ThrowableFactory;

abstract class ValkyrjaInvalidArgumentException extends InvalidArgumentException implements ValkyrjaThrowable
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getTraceCode(): string
    {
        return ThrowableFactory::getTraceCode($this);
    }
}
