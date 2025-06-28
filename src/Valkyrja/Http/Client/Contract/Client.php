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

namespace Valkyrja\Http\Client\Contract;

use Valkyrja\Http\Message\Request\Contract\Request;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface Client.
 *
 * @author Melech Mizrachi
 */
interface Client
{
    /**
     * Send a request and recieve a response.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sendRequest(Request $request): Response;
}
