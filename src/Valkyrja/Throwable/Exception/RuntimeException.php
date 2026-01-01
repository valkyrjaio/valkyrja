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

use Override;
use RuntimeException as PhpRuntimeException;
use Valkyrja\Throwable\Contract\Throwable;
use Valkyrja\Throwable\Handler\ThrowableHandler;

/**
 * Class RuntimeException.
 */
class RuntimeException extends PhpRuntimeException implements Throwable
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
