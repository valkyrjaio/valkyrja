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

namespace Valkyrja\Http\Client\Manager\Contract;

use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

/**
 * Interface ClientContract.
 */
interface ClientContract
{
    /**
     * Send a request and recieve a response.
     *
     * @param RequestContract $request
     *
     * @return ResponseContract
     */
    public function sendRequest(RequestContract $request): ResponseContract;
}
