<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Client\Manager;

use JsonException;
use Override;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Type\Object\Factory\ObjectFactory;

class LogClient implements ClientContract
{
    public function __construct(
        protected LoggerContract $logger,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function sendRequest(RequestContract $request): ResponseContract
    {
        $optionsString = ObjectFactory::toString($request);

        $this->logger->info(
            static::class . " request: {$request->getMethod()->value}, uri {$request->getUri()->__toString()}, options $optionsString"
        );

        return new EmptyResponse();
    }
}
