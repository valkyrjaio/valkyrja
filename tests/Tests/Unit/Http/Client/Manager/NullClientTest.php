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

use Valkyrja\Http\Client\Manager\NullClient;
use Valkyrja\Http\Message\Request\Request;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the NullClient service.
 */
final class NullClientTest extends TestCase
{
    public function testSendRequest(): void
    {
        $client  = new NullClient();
        $request = new Request();

        $response = $client->sendRequest($request);

        self::assertInstanceOf(EmptyResponse::class, $response);
    }
}
