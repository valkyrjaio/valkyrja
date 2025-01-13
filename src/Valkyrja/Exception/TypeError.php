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
    public function getTraceCode(): string
    {
        return ErrorHandler::getTraceCode($this);
    }
}
