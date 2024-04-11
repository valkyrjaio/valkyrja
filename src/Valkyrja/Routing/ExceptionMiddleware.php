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

namespace Valkyrja\Routing;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

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
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return Response
     */
    public static function exception(Request $request, Response $response): Response;
}
