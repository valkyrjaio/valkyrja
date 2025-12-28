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

namespace Valkyrja\Tests\Unit\Http\Client;

use JsonException;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Client\LogClient;
use Valkyrja\Http\Message\Request\Request;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the LogClient service.
 *
 * @author Melech Mizrachi
 */
class LogClientTest extends TestCase
{
    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testSendRequest(): void
    {
        $logger = $this->createMock(Logger::class);

        $client  = new LogClient($logger);
        $request = new Request();

        $logger->expects(self::once())->method('info');

        $response = $client->sendRequest($request);

        self::assertInstanceOf(EmptyResponse::class, $response);
    }
}
