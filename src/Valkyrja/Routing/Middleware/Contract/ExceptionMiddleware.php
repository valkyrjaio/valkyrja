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

namespace Valkyrja\Routing\Middleware\Contract;

use Valkyrja\Http\Request\Contract\ServerRequest;
use Valkyrja\Http\Response\Contract\Response;

/**
 * Interface ExceptionMiddleware.
 *
 * @author Melech Mizrachi
 */
interface ExceptionMiddleware
{
    /**
     * Middleware handler for after an exception has been captured during dispatch.
     *
     * @param ServerRequest $request  The request
     * @param Response      $response The response
     *
     * @return Response
     */
    public static function exception(ServerRequest $request, Response $response): Response;
}
