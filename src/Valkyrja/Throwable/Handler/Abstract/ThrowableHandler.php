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

namespace Valkyrja\Throwable\Handler\Abstract;

use Override;
use Throwable;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

use function md5;

abstract class ThrowableHandler implements ThrowableHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTraceCode(Throwable $throwable): string
    {
        return md5($throwable::class . $throwable->getTraceAsString());
    }
}
