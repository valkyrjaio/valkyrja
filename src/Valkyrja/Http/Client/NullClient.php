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

namespace Valkyrja\Http\Client;

use Override;
use Valkyrja\Http\Client\Contract\Client as Contract;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\Request;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class NullClient.
 *
 * @author Melech Mizrachi
 */
class NullClient implements Contract
{
    public function __construct(
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendRequest(Request $request): Response
    {
        return $this->responseFactory->createResponse();
    }
}
