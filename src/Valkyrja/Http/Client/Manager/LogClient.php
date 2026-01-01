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

namespace Valkyrja\Http\Client\Manager;

use JsonException;
use Override;
use Valkyrja\Http\Client\Manager\Contract\ClientContract as Contract;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Type\BuiltIn\Support\Obj;

/**
 * Class LogClient.
 *
 * @author Melech Mizrachi
 */
class LogClient implements Contract
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
        $optionsString = Obj::toString($request);

        $this->logger->info(
            static::class . " request: {$request->getMethod()->value}, uri {$request->getUri()->__toString()}, options $optionsString"
        );

        return new EmptyResponse();
    }
}
