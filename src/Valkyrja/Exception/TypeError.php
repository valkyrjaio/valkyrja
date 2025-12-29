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

namespace Valkyrja\Exception;

use Override;
use Valkyrja\Exception\Handler\ExceptionHandler;

/**
 * Class TypeError.
 *
 * @author Melech Mizrachi
 */
class TypeError extends \TypeError implements Throwable
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getTraceCode(): string
    {
        return ExceptionHandler::getTraceCode($this);
    }
}
