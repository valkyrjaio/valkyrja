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

namespace Valkyrja\Http\Server\Handler\Contract;

use Throwable;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

/**
 * Interface ExceptionResponseRequestHandlerContract.
 */
interface ExceptionResponseRequestHandlerContract extends RequestHandlerContract
{
    public function createResponseFromException(Throwable $exception): ResponseContract;
}
