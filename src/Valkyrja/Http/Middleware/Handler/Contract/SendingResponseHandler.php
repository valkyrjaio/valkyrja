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

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface SendingResponseHandler.
 *
 * @author Melech Mizrachi
 */
interface SendingResponseHandler
{
    /**
     * Middleware handler for before a response has been sent.
     */
    public function sendingResponse(ServerRequest $request, Response $response): Response;
}
