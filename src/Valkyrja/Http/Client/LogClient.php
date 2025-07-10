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

use JsonException;
use Override;
use Valkyrja\Http\Client\Contract\Client as Contract;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\Request;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Type\BuiltIn\Support\Obj;

/**
 * Class LogClient.
 *
 * @author Melech Mizrachi
 */
class LogClient implements Contract
{
    public function __construct(
        protected Logger $logger,
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function sendRequest(Request $request): Response
    {
        $optionsString = Obj::toString($request);

        $this->logger->info(
            static::class . " request: {$request->getMethod()->value}, uri {$request->getUri()->__toString()}, options $optionsString"
        );

        return $this->responseFactory->createResponse();
    }
}
