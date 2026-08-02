<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Client\Manager;

use JsonException;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Client\Manager\LogClient;
use Valkyrja\Http\Message\Request\Request;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the LogClient service.
 */
final class LogClientTest extends TestCase
{
    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testSendRequest(): void
    {
        $logger = $this->createMock(LoggerContract::class);

        $client  = new LogClient($logger);
        $request = new Request();

        $logger->expects($this->once())->method('info');

        $response = $client->sendRequest($request);

        self::assertInstanceOf(EmptyResponse::class, $response);
    }
}
