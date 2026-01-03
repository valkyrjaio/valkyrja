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

namespace Valkyrja\Throwable\Exception;

use InvalidArgumentException as PhpInvalidArgumentException;
use Override;
use Valkyrja\Throwable\Contract\Throwable;
use Valkyrja\Throwable\Handler\Abstract\ThrowableHandler;

class InvalidArgumentException extends PhpInvalidArgumentException implements Throwable
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getTraceCode(): string
    {
        return ThrowableHandler::getTraceCode($this);
    }
}
